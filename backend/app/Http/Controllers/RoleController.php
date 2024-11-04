<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return $roles;
    }

    public function show($id)
    {
        $role = Role::where('id', $id)->first();

        return $role;
    }
}
