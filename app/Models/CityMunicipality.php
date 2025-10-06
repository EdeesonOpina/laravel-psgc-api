<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CityMunicipality extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'province_id', 'region_id', 'type', 'income_class', 'urban_rural', 'old_name', 'status'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function barangays()
    {
        return $this->hasMany(Barangay::class);
    }
}
