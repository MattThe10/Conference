<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
        'street_address',
        'city',
        'district',
        'region',
        'postal_code',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }
}
