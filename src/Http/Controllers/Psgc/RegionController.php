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
        $perPage = $request->get('per_page');
        
        $cacheKey = "v1:regions:q={$q}:per=" . ($perPage ?: 'all');
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $perPage) {
            $query = Region::query()
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")
                                        ->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name');
            
            if ($perPage) {
                $per = min(max((int) $perPage, 1), 200);
                $rows = $query->paginate($per);
                
                return response()->json([
                    'table' => 'regions',
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
            } else {
                $rows = $query->get();
                
                return response()->json([
                    'table' => 'regions',
                    'rows' => $rows
                ]);
            }
        });
    }

    public function show(string $code)
    {
        $key = "v1:regions:{$code}";
        $region = Cache::remember($key, now()->addMinutes(30), fn() =>
            Region::where('code',$code)->first()
        );
        abort_unless($region, 404, 'Region not found');
        
        return response()->json([
            'table' => 'regions',
            'rows' => [$region]
        ]);
    }
}
