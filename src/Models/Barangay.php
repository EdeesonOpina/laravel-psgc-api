<?php

namespace EdeesonOpina\PsgcApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barangay extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'city_municipality_id', 'province_id', 'region_id', 'old_name', 'status'];

    public function citymunicipality()
    {
        return $this->belongsTo(CityMunicipality::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
