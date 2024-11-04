<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'iso_code',
        'name',
    ];

    public function universities()
    {
        return $this->hasMany(University::class);
    }
    
    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }
}
