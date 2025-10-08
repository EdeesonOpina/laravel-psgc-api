<?php

namespace EdeesonOpina\PsgcApi\Http\Controllers\Psgc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EdeesonOpina\PsgcApi\Models\Province;
use EdeesonOpina\PsgcApi\Models\Region;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $regionId = $request->get('region_id');

        $cacheKey = "v1:provinces:q={$q}:region={$regionId}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $regionId) {
            $provinces = Province::query()
                ->when($regionId, fn($s) => $s->where('region_id', $regionId))
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name')
                ->get();
            
            return response()->json($provinces);
        });
    }

    public function show(string $id)
    {
        $province = Cache::remember("v1:province:{$id}", now()->addMinutes(30),
            fn() => Province::find($id)
        );
        abort_unless($province, 404, 'Province not found');
        
        return response()->json($province);
    }
}
