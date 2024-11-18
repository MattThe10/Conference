<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Retrieve the current authenticated user
    |--------------------------------------------------------------------------
    |
    | This method fetches and returns the currently authenticated user's data
    | as a JSON response.
    |
    */

    public function getCurrentUser()
    {
        $user = Auth::user();

        return response()->json($user);
    }

    /*
    |--------------------------------------------------------------------------
    | Update the profile of the current authenticated user
    |--------------------------------------------------------------------------
    |
    | This method validates the input data, verifies the current password,
    | and updates the user's email, name, surname, faculty, and optionally
    | updates the password if a new one is provided.
    |
    */

    public function updateCurrentUser(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password'  => ['required'],
            'new_password'      => ['nullable', Password::defaults()],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'name'              => ['required', 'string', 'max:255'],
            'surname'           => ['required', 'string', 'max:255'],
            'faculty_id'        => ['required', 'exists:faculties,id'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Wrong password.'
            ], 401);
        }

        $user->update([
            'email'         => $validated['email'],
            'name'          => $validated['name'],
            'surname'       => $validated['surname'],
            'faculties_id'  => $validated['faculty_id'],
            'password'      => $validated['new_password'] ? Hash::make($validated['new_password']) : $user->password,
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ]);
    }
}
