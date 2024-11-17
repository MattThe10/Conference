<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Faculty
 * 
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property int $universities_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property University $university
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Faculty extends Model
{
	protected $table = 'faculties';

	protected $casts = [
		'universities_id' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'city',
		'postal_code',
		'country',
		'universities_id'
	];

	public function university()
	{
		return $this->belongsTo(University::class, 'universities_id');
	}

	public function users()
	{
		return $this->hasMany(User::class, 'faculties_id');
	}
}
