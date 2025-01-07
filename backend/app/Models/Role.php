<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $key
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';

	protected $fillable = [
		'key',
		'name'
	];

	public function users()
	{
		return $this->hasMany(User::class, 'roles_id');
	}

	public function index(Request $request) 
	{
		// Initialize the query  
		$roles = Role::query(); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search !=	 null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$roles = $roles->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(key) LIKE ?', ['%' . $search . '%']) // Filter by key
					->orwhereRaw('LOWER(name) LIKE?', ['%' . $search . '%']); // Filter by name 
			});
		}	
					
		$roles = $roles->get(); 
				
		// Return the resulting roles as a JSON response 
		return response()->json($roles);
	}
}
