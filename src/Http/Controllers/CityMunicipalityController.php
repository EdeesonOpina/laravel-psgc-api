<?php

namespace EdeesonOpina\PsgcApi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EdeesonOpina\PsgcApi\Models\CityMunicipality;
use EdeesonOpina\PsgcApi\Models\Province;
use EdeesonOpina\PsgcApi\Models\Region;

class CityMunicipalityController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $provinceCode = $request->string('province_code')->toString();
        $regionCode = $request->string('region_code')->toString();
        $type = $request->string('type')->toString(); // City|Municipality
        $per = min(max((int) $request->get('per_page', 50), 1), 200);

        $cacheKey = "v1:cm:q={$q}:prov={$provinceCode}:reg={$regionCode}:type={$type}:per={$per}:page=".(int)$request->get('page',1);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $provinceCode, $regionCode, $type, $per) {
            $rows = CityMunicipality::query()
                ->when($provinceCode, function ($s) use ($provinceCode) {
                    $provinceId = optional(Province::where('code',$provinceCode)->first())->id;
                    $s->where('province_id', $provinceId ?? 0);
                })
                ->when($regionCode, function ($s) use ($regionCode) {
                    $regionId = optional(Region::where('code',$regionCode)->first())->id;
                    $s->where('region_id', $regionId ?? 0);
                })
                ->when($type, fn($s) => $s->where('type', $type))
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name')
                ->paginate($per);

            return response()->json($rows);
        });
    }

    public function show(string $code)
    {
        $row = Cache::remember("v1:cm:{$code}", now()->addMinutes(30),
            fn() => CityMunicipality::where('code',$code)->first()
        );
        abort_unless($row, 404, 'City/Municipality not found');
        return response()->json($row);
    }
}
