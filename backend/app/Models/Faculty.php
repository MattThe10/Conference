<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'street_address',
        'city',
        'district',
        'region',
        'postal_code',
        'country_id',
        'university_id',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
