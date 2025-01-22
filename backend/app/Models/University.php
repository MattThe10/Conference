<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class University
 * 
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Conference[] $conferences
 * @property Collection|Faculty[] $faculties
 *
 * @package App\Models
 */
class University extends Model
{
	protected $table = 'universities';

	protected $fillable = [
		'name',
		'address',
		'city',
		'postal_code',
		'country'
	];

	public function conferences()
	{
		return $this->hasMany(Conference::class, 'location_id');
	}

	public function faculties()
	{
		return $this->hasMany(Faculty::class, 'universities_id');
	}
}
