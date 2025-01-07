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

	public function index(Request $request) 
	{
		// Default number of records per page 
		$per_page = 10; 
		
		// Initialize the query  
		$users = User::query();
		//	->with(['conference']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search != null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$users = $users->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']) // Filter by name
					->orwhereRaw('LOWER(surname) LIKE?', ['%' . $search . '%']) // Filter by surname 
					->orwhereRaw('LOWER(email) LIKE ?', ['%'. $search . '%']) // Filter by email 
					->orWhereHas('faculty', function ($query) use ($search) {
						$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']); // Filter by related faculty name
					});
			});
		}	
					
		//Determine whether to paginate or return all records 
		if ($request->has('search') || $request->has('page')) { 
			// Paginate results if 'search' or 'page' is present in the request 
			$users = $users->paginate($per_page); 
		} else { 
			//Return all results if neither 'search' nor 'page' is specified 
			$users = $users->get(); 
		}
		
		// Return the resulting users as a JSON response 
		return response()->json($users);
	}

}
