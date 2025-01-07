<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Retrieve all roles
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `roles` table and 
    | returns them as a JSON response.
    |
    */
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
    /*
    |--------------------------------------------------------------------------
    | Retrieve a specific role by its ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a role record based on the provided ID and 
    | returns it as a JSON response. If the role is not found, it
    | returns `null`.
    |
    */

    public function show($role_id)
    {
        $role = Role::findOrFail($role_id);

        return response()->json($role);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created role in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data and creates a new role
    | record in the `roles` table. It returns a 201 status code upon
    | successful creation.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key'   => ['required', 'string'],
            'name'  => ['nullable', 'string', 'max:255'],
        ]);

        Role ::create([
            'key'   => $validated['key'],
            'name'  => $validated['name'] ?? null,
        ]);

        return response()->json([
            'message' => 'Successfully stored.'
        ], 201); // 201 CREATED
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified role in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data, finds the specified role
    | by its ID, and updates its information. It returns a 200 status code
    | upon successful update.
    |
    */

    public function update(Request $request, $role_id)
    {
        $validated = $request->validate([
            'key'   => ['required', 'string'],
            'name'  => ['nullable', 'string', 'max:255'],
        ]);

        $role = Role::findOrFail($role_id);

        $role->update([
            'key'   => $validated['key'],
            'name'  => $validated['name'] ?? null,
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ], 200); // 200 OK
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified role from the database.
    |--------------------------------------------------------------------------
    |
    | This method deletes the specified role record based on the provided ID.
    | It returns a 200 status code upon successful deletion.
    |
    */

    public function destroy($role_id)
    {
        $role = Role::findOrFail($role_id);
        $role->delete();

        return response()->json([
            'message' => 'Successfully deleted.'
        ], 200); // 200 OK
    }
}
