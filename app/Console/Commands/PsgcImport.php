<?php

namespace App\Console\Commands;

/**
 * PSGC Import Command
 * 
 * Imports Philippine Standard Geographic Code (PSGC) data from CSV files
 * into the database for API consumption.
 * 
 * Data Source: @jobuntux/psgc (https://www.npmjs.com/package/@jobuntux/psgc)
 * Official Source: Philippine Statistics Authority (PSA)
 * Developer: Edeeson Opina (https://edeesonopina.vercel.app/)
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\{Region, Province, CityMunicipality, Barangay};
use SplFileObject;

class PsgcImport extends Command
{
    protected $signature = 'psgc:import
        {--regions= : Path to regions CSV file}
        {--provinces= : Path to provinces CSV file}
        {--city_municipalities= : Path to city/municipalities CSV file}
        {--barangays= : Path to barangays CSV file}
        {--truncate : Truncate all PSGC tables before import}
        {--dry-run : Validate only (no DB commit)}';

    protected $description = 'Import PSGC data from CSV files (UTF-8, comma-delimited) into regions, provinces, city_municipalities, and barangays tables.';

    public function handle(): int
    {
        // optional wipe
        if ($this->option('truncate')) {
            if (!$this->confirm('⚠ This will DELETE all PSGC data. Continue?')) {
                return self::SUCCESS;
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Barangay::truncate();
            CityMunicipality::truncate();
            Province::truncate();
            Region::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->warn('✅ Tables truncated.');
        }

        DB::beginTransaction();
        try {
            if ($f = $this->option('regions'))            $this->importRegions($f);
            if ($f = $this->option('provinces'))          $this->importProvinces($f);
            if ($f = $this->option('city_municipalities'))$this->importCityMunicipalities($f);
            if ($f = $this->option('barangays'))          $this->importBarangays($f);

            if ($this->option('dry-run')) {
                DB::rollBack();
                $this->info('🧪 Dry-run complete — nothing saved.');
            } else {
                DB::commit();
                $this->info('✅ Import committed.');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('❌ ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /* ------------------------------------------------------------ */
    /** iterate csv */
    private function csv(string $path): \Generator
    {
        if (!is_file($path)) {
            throw new \RuntimeException("File not found: {$path}");
        }

        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(',');

        $headers = null;
        foreach ($file as $row) {
            if ($row === [null] || $row === false) continue;
            if ($headers === null) {
                $headers = array_map('trim', $row);
                continue;
            }
            $assoc = array_combine($headers, array_map('trim', $row));
            if (!$assoc) continue;
            yield $assoc;
        }
    }

    /* ------------------------------------------------------------ */
    private function importRegions(string $path): void
    {
        $this->line("→ Importing regions from {$path}");
        foreach ($this->csv($path) as $r) {
            Region::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'         => $r['name'] ?? '',
                    'short_name'   => $r['short_name'] ?? null,
                    'island_group' => $r['island_group'] ?? null,
                    'status'       => $r['status'] ?? 'active',
                ]
            );
        }
        $this->info('✓ Regions imported.');
    }

    private function importProvinces(string $path): void
    {
        $this->line("→ Importing provinces from {$path}");
        foreach ($this->csv($path) as $r) {
            $region = Region::where('code', $r['region_code'] ?? null)->first();
            Province::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'       => $r['name'] ?? '',
                    'region_id'  => $region?->id,
                    'old_name'   => $r['old_name'] ?? null,
                    'status'     => $r['status'] ?? 'active',
                ]
            );
        }
        $this->info('✓ Provinces imported.');
    }

    private function importCityMunicipalities(string $path): void
    {
        $this->line("→ Importing city_municipalities from {$path}");
        foreach ($this->csv($path) as $r) {
            $province = Province::where('code', $r['province_code'] ?? null)->first();
            $region   = Region::where('code', $r['region_code'] ?? null)->first();

            CityMunicipality::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'          => $r['name'] ?? '',
                    'province_id'   => $province?->id,
                    'region_id'     => $region?->id,
                    'type'          => in_array($r['type'] ?? 'Municipality', ['City','Municipality']) ? $r['type'] : 'Municipality',
                    'income_class'  => $r['income_class'] ?? null,
                    'urban_rural'   => $r['urban_rural'] ?? null,
                    'old_name'      => $r['old_name'] ?? null,
                    'status'        => $r['status'] ?? 'active',
                ]
            );
        }
        $this->info('✓ City/Municipalities imported.');
    }

    private function importBarangays(string $path): void
    {
        $this->line("→ Importing barangays from {$path}");
        foreach ($this->csv($path) as $r) {
            $cm     = CityMunicipality::where('code', $r['city_municipality_code'] ?? null)->first();
            $prov   = Province::where('code', $r['province_code'] ?? null)->first();
            $region = Region::where('code', $r['region_code'] ?? null)->first();

            Barangay::updateOrCreate(
                ['code' => $r['code']],
                [
                    'name'                  => $r['name'] ?? '',
                    'city_municipality_id'  => $cm?->id,
                    'province_id'           => $prov?->id,
                    'region_id'             => $region?->id,
                    'old_name'              => $r['old_name'] ?? null,
                    'status'                => $r['status'] ?? 'active',
                ]
            );
        }
        $this->info('✓ Barangays imported.');
    }
}
