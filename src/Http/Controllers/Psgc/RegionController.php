<?php

namespace EdeesonOpina\PsgcApi\Http\Controllers\Psgc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use EdeesonOpina\PsgcApi\Models\Region;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        
        $cacheKey = "v1:regions:q={$q}";
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q) {
            $regions = Region::query()
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")
                                        ->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name')
                ->get();
            
            return response()->json($regions);
        });
    }

    public function show(string $id)
    {
        $key = "v1:regions:{$id}";
        $region = Cache::remember($key, now()->addMinutes(30), fn() =>
            Region::find($id)
        );
        abort_unless($region, 404, 'Region not found');
        
        return response()->json($region);
    }
}
