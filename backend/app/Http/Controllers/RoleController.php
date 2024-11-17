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

    public function index()
    {
        $roles = Role::all();

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
        $role = Role::find($role_id);

        return response()->json($role);
    }
}
