<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    public function show($role_id)
    {
        $role = Role::find($role_id);

        return response()->json($role);
    }
}
