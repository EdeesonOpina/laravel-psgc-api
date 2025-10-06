<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Province;
use App\Models\Region;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $regionCode = $request->string('region_code')->toString();
        $per = min(max((int) $request->get('per_page', 50), 1), 200);

        $cacheKey = "v1:provinces:q={$q}:region={$regionCode}:per={$per}:page=".(int)$request->get('page',1);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($q, $regionCode, $per) {
            $rows = Province::query()
                ->when($regionCode, function ($s) use ($regionCode) {
                    $regionId = optional(Region::where('code',$regionCode)->first())->id;
                    $s->where('region_id', $regionId ?? 0);
                })
                ->when($q, fn($s) => $s->where('name','LIKE',"%{$q}%")->orWhere('code','LIKE',"%{$q}%"))
                ->orderBy('name')
                ->paginate($per);
            return response()->json($rows);
        });
    }

    public function show(string $code)
    {
        $row = Cache::remember("v1:province:{$code}", now()->addMinutes(30),
            fn() => Province::where('code',$code)->first()
        );
        abort_unless($row, 404, 'Province not found');
        return response()->json($row);
    }
}
