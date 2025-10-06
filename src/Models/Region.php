<?php

namespace EdeesonOpina\PsgcApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'short_name', 'island_group', 'status'];

    public function provinces()
    {
        return $this->hasMany(Province::class);
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
