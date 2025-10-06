<?php

namespace EdeesonOpina\PsgcApi\Http\Controllers\Psgc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EdeesonOpina\PsgcApi\Models\Barangay;
use EdeesonOpina\PsgcApi\Models\CityMunicipality;
use EdeesonOpina\PsgcApi\Models\Province;
use EdeesonOpina\PsgcApi\Models\Region;

class BarangayController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $citymunCode = $request->string('citymun_code')->toString();
        $provinceCode = $request->string('province_code')->toString();
        $regionCode = $request->string('region_code')->toString();
        $per = min(max((int) $request->get('per_page', 50), 1), 200);

        $cacheKey = "v1:brgy:q={$q}:cm={$citymunCode}:prov={$provinceCode}:reg={$regionCode}:per={$per}:page=".(int)$request->get('page',1);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $citymunCode, $provinceCode, $regionCode, $per) {
            $rows = Barangay::query()
                ->when($citymunCode, function ($s) use ($citymunCode) {
                    $cmId = optional(CityMunicipality::where('code',$citymunCode)->first())->id;
                    $s->where('citymun_id', $cmId ?? 0);
                })
                ->when($provinceCode, function ($s) use ($provinceCode) {
                    $provinceId = optional(Province::where('code',$provinceCode)->first())->id;
                    $s->where('province_id', $provinceId ?? 0);
                })
                ->when($regionCode, function ($s) use ($regionCode) {
                    $regionId = optional(Region::where('code',$regionCode)->first())->id;
                    $s->where('region_id', $regionId ?? 0);
                })
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name')
                ->paginate($per);

            return response()->json([
                'table' => 'barangays',
                'rows' => $rows->items(),
                'pagination' => [
                    'current_page' => $rows->currentPage(),
                    'per_page' => $rows->perPage(),
                    'total' => $rows->total(),
                    'last_page' => $rows->lastPage(),
                    'from' => $rows->firstItem(),
                    'to' => $rows->lastItem()
                ]
            ]);
        });
    }

    public function show(string $code)
    {
        $row = Cache::remember("v1:brgy:{$code}", now()->addMinutes(30),
            fn() => Barangay::where('code',$code)->first()
        );
        abort_unless($row, 404, 'Barangay not found');
        
        return response()->json([
            'table' => 'barangays',
            'rows' => [$row]
        ]);
    }
}