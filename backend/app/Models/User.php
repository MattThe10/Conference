<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $surname
 * @property int $roles_id
 * @property int $faculties_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Faculty $faculty
 * @property Role $role
 * @property Collection|Review[] $reviews
 * @property Collection|Article[] $articles
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'users';

	protected $casts = [
		'roles_id' => 'int',
		'faculties_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'email',
		'password',
		'name',
		'surname',
		'roles_id',
		'faculties_id'
	];

	public function faculty()
	{
		return $this->belongsTo(Faculty::class, 'faculties_id');
	}

	public function role()
	{
		return $this->belongsTo(Role::class, 'roles_id');
	}

	public function reviews()
	{
		return $this->hasMany(Review::class, 'users_id');
	}

	public function articles()
	{
		return $this->belongsToMany(Article::class, 'users_has_articles', 'users_id', 'articles_id')
					->withPivot('id')
					->withTimestamps();
	}
}
