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
        $citymunId = $request->get('city_municipality_id');
        $provinceId = $request->get('province_id');
        $regionId = $request->get('region_id');
        $limit = $request->get('limit');

        $cacheKey = "v1:brgy:q={$q}:cm={$citymunId}:prov={$provinceId}:reg={$regionId}:limit={$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $citymunId, $provinceId, $regionId, $limit) {
            $query = Barangay::query()
                ->when($citymunId, fn($s) => $s->where('city_municipality_id', $citymunId))
                ->when($provinceId, fn($s) => $s->where('province_id', $provinceId))
                ->when($regionId, fn($s) => $s->where('region_id', $regionId))
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name');
            
            if ($limit) {
                $limit = min(max((int) $limit, 1), 1000); // Max 1000 records
                $barangays = $query->limit($limit)->get();
            } else {
                $barangays = $query->get();
            }
            
            return response()->json($barangays);
        });
    }

    public function show(string $id)
    {
        $barangay = Cache::remember("v1:brgy:{$id}", now()->addMinutes(30),
            fn() => Barangay::find($id)
        );
        abort_unless($barangay, 404, 'Barangay not found');
        
        return response()->json($barangay);
    }
}