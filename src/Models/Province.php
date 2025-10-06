<?php

namespace EdeesonOpina\PsgcApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'region_id', 'old_name', 'status'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function citiesMunicipalities()
    {
        return $this->hasMany(CityMunicipality::class);
    }
    
    public function barangays()
    {
        return $this->hasMany(Barangay::class);
    }
}
