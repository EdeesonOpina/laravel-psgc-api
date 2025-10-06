<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Region;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $per = min(max((int) $request->get('per_page', 50), 1), 200);

        $cacheKey = "v1:regions:q={$q}:per={$per}:page=" . (int)($request->get('page',1));
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $per) {
            $rows = Region::query()
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")
                                        ->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name')
                ->paginate($per);
            return response()->json($rows);
        });
    }

    public function show(string $code)
    {
        $key = "v1:regions:{$code}";
        $region = Cache::remember($key, now()->addMinutes(30), fn() =>
            Region::where('code',$code)->first()
        );
        abort_unless($region, 404, 'Region not found');
        return response()->json($region);
    }
}
