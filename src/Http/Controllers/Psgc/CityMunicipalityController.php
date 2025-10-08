<?php

namespace EdeesonOpina\PsgcApi\Http\Controllers\Psgc;

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
        $provinceId = $request->get('province_id');
        $regionId = $request->get('region_id');
        $type = $request->string('type')->toString(); // City|Municipality
        $limit = $request->get('limit');

        $cacheKey = "v1:cm:q={$q}:prov={$provinceId}:reg={$regionId}:type={$type}:limit={$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $provinceId, $regionId, $type, $limit) {
            $query = CityMunicipality::query()
                ->when($provinceId, fn($s) => $s->where('province_id', $provinceId))
                ->when($regionId, fn($s) => $s->where('region_id', $regionId))
                ->when($type, fn($s) => $s->where('type', $type))
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name');
            
            if ($limit) {
                $limit = min(max((int) $limit, 1), 1000); // Max 1000 records
                $cityMunicipalities = $query->limit($limit)->get();
            } else {
                $cityMunicipalities = $query->get();
            }
            
            return response()->json($cityMunicipalities);
        });
    }

    public function show(string $id)
    {
        $cityMunicipality = Cache::remember("v1:cm:{$id}", now()->addMinutes(30),
            fn() => CityMunicipality::find($id)
        );
        abort_unless($cityMunicipality, 404, 'City/Municipality not found');
        
        return response()->json($cityMunicipality);
    }
}
