<?php

namespace EdeesonOpina\PsgcApi\Console\Commands;

/**
 * PSGC Export Command
 * 
 * Exports Philippine Standard Geographic Code (PSGC) data from the database
 * to CSV files for backup, sharing, or migration purposes.
 * 
 * Data Source: @jobuntux/psgc (https://www.npmjs.com/package/@jobuntux/psgc)
 * Official Source: Philippine Statistics Authority (PSA)
 * Developer: Edeeson Opina (https://edeesonopina.vercel.app/)
 */

use Illuminate\Console\Command;
use EdeesonOpina\PsgcApi\Models\{Region, Province, CityMunicipality, Barangay};
use Illuminate\Support\Facades\Storage;

class PsgcExport extends Command
{
    protected $signature = 'psgc:export
        {--output=exports : Output directory for CSV files}
        {--regions : Export regions only}
        {--provinces : Export provinces only}
        {--city_municipalities : Export cities/municipalities only}
        {--barangays : Export barangays only}
        {--all : Export all data (default)}
        {--format=csv : Export format (csv, json)}
        {--status=active : Filter by status (active, inactive, all)}';

    protected $description = 'Export PSGC data from database to CSV/JSON files for backup, sharing, or migration.';

    public function handle(): int
    {
        $outputDir = $this->option('output');
        $format = $this->option('format');
        $status = $this->option('status');
        
        // Create output directory if it doesn't exist
        $fullPath = storage_path('app/' . $outputDir);
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $this->info("🚀 Starting PSGC data export...");
        $this->info("📁 Output directory: {$fullPath}");
        $this->info("📄 Format: " . strtoupper($format));
        $this->info("🔍 Status filter: {$status}");

        $exported = 0;

        try {
            // Export regions
            if ($this->shouldExport('regions')) {
                $count = $this->exportRegions($fullPath, $format, $status);
                $exported += $count;
                $this->info("✓ Exported {$count} regions");
            }

            // Export provinces
            if ($this->shouldExport('provinces')) {
                $count = $this->exportProvinces($fullPath, $format, $status);
                $exported += $count;
                $this->info("✓ Exported {$count} provinces");
            }

            // Export cities/municipalities
            if ($this->shouldExport('city_municipalities')) {
                $count = $this->exportCityMunicipalities($fullPath, $format, $status);
                $exported += $count;
                $this->info("✓ Exported {$count} cities/municipalities");
            }

            // Export barangays
            if ($this->shouldExport('barangays')) {
                $count = $this->exportBarangays($fullPath, $format, $status);
                $exported += $count;
                $this->info("✓ Exported {$count} barangays");
            }

            $this->info("\n✅ Export completed successfully!");
            $this->info("📊 Total records exported: {$exported}");
            $this->info("📁 Files saved to: {$fullPath}");

        } catch (\Throwable $e) {
            $this->error('❌ Export failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    private function shouldExport(string $type): bool
    {
        if ($this->option('all')) {
            return true;
        }

        return $this->option($type);
    }

    private function exportRegions(string $outputDir, string $format, string $status): int
    {
        $query = Region::query();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $regions = $query->orderBy('code')->get();

        if ($format === 'csv') {
            $this->exportToCsv($regions, $outputDir . '/regions.csv', [
                'code', 'name', 'short_name', 'island_group', 'status'
            ]);
        } else {
            $this->exportToJson($regions, $outputDir . '/regions.json');
        }

        return $regions->count();
    }

    private function exportProvinces(string $outputDir, string $format, string $status): int
    {
        $query = Province::with('region');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $provinces = $query->orderBy('code')->get();

        if ($format === 'csv') {
            $this->exportToCsv($provinces, $outputDir . '/provinces.csv', [
                'code', 'name', 'region_code', 'old_name', 'status'
            ], function ($province) {
                return [
                    'code' => $province->code,
                    'name' => $province->name,
                    'region_code' => $province->region->code ?? '',
                    'old_name' => $province->old_name,
                    'status' => $province->status,
                ];
            });
        } else {
            $this->exportToJson($provinces, $outputDir . '/provinces.json');
        }

        return $provinces->count();
    }

    private function exportCityMunicipalities(string $outputDir, string $format, string $status): int
    {
        $query = CityMunicipality::with(['province', 'region']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $citiesMunicipalities = $query->orderBy('code')->get();

        if ($format === 'csv') {
            $this->exportToCsv($citiesMunicipalities, $outputDir . '/city_municipalities.csv', [
                'code', 'name', 'province_code', 'region_code', 'type', 'income_class', 'urban_rural', 'old_name', 'status'
            ], function ($cityMunicipality) {
                return [
                    'code' => $cityMunicipality->code,
                    'name' => $cityMunicipality->name,
                    'province_code' => $cityMunicipality->province->code ?? '',
                    'region_code' => $cityMunicipality->region->code ?? '',
                    'type' => $cityMunicipality->type,
                    'income_class' => $cityMunicipality->income_class,
                    'urban_rural' => $cityMunicipality->urban_rural,
                    'old_name' => $cityMunicipality->old_name,
                    'status' => $cityMunicipality->status,
                ];
            });
        } else {
            $this->exportToJson($citiesMunicipalities, $outputDir . '/city_municipalities.json');
        }

        return $citiesMunicipalities->count();
    }

    private function exportBarangays(string $outputDir, string $format, string $status): int
    {
        $query = Barangay::with(['cityMunicipality', 'province', 'region']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $barangays = $query->orderBy('code')->get();

        if ($format === 'csv') {
            $this->exportToCsv($barangays, $outputDir . '/barangays.csv', [
                'code', 'name', 'city_municipality_code', 'province_code', 'region_code', 'old_name', 'status'
            ], function ($barangay) {
                return [
                    'code' => $barangay->code,
                    'name' => $barangay->name,
                    'city_municipality_code' => $barangay->cityMunicipality->code ?? '',
                    'province_code' => $barangay->province->code ?? '',
                    'region_code' => $barangay->region->code ?? '',
                    'old_name' => $barangay->old_name,
                    'status' => $barangay->status,
                ];
            });
        } else {
            $this->exportToJson($barangays, $outputDir . '/barangays.json');
        }

        return $barangays->count();
    }

    private function exportToCsv($data, string $filePath, array $headers, ?callable $transformer = null): void
    {
        $file = fopen($filePath, 'w');
        
        // Write headers
        fputcsv($file, $headers);
        
        // Write data
        foreach ($data as $item) {
            if ($transformer) {
                $row = $transformer($item);
            } else {
                $row = $item->only($headers);
            }
            
            fputcsv($file, array_values($row));
        }
        
        fclose($file);
    }

    private function exportToJson($data, string $filePath): void
    {
        $jsonData = $data->toArray();
        file_put_contents($filePath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
