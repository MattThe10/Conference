<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Retrieve all users
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `users` table and 
    | returns them as a JSON response.
    |
    */

    public function index(Request $request) 
    {
        // Initialize the query  
        $users = User::query()
            ->with(['faculty', 'role', 'reviews', 'articles']);
            
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
                    
        $users = $users->get(); 
        
        // Return the resulting users as a JSON response 
        return response()->json($users);
    }

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
        $user = Auth::user()
            ->load(['faculty', 'role', 'reviews', 'articles']);

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
            'current_password'              => ['required'],
            'new_password'                  => ['nullable', Rules\Password::min(8)->mixedCase()->numbers()],
            'new_password_confirmation'     => ['nullable', 'same:new_password'],
            'email'                         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'name'                          => ['required', 'string', 'max:255'],
            'surname'                       => ['required', 'string', 'max:255'],
            'faculty_id'                    => ['required', 'exists:faculties,id'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Wrong current password.'
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

    /*
    |--------------------------------------------------------------------------
    | Retrieve all articles by its user ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a article record based on the provided ID and 
    | returns it as a JSON response. If the article is not found, it
    | returns `null`.
    |
    */

    public function getUserArticles($user_id)
    {
        $user = User::findOrFail($user_id);

        $articles = $user->articles()->with(['article_status', 'conference', 'documents', 'reviews', 'users'])->get();

        return response()->json($articles);
    }

    public function getArticlesForReview($user_id)
    {
        $user = User::findOrFail($user_id);

        $articles = $user->reviews()->with(['article.users'])->get();

        return response()->json($articles);
    }

    public function update(Request $request, $user_id)
    {
        $validated = $request->validate([
            'new_password'      => ['nullable', Password::defaults()],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user_id],
            'name'              => ['required', 'string', 'max:255'],
            'surname'           => ['required', 'string', 'max:255'],
            'faculty_id'        => ['required', 'exists:faculties,id'],
            'role_id'           => ['required', 'exists:roles,id'],
        ]);

        $user = User::findOrFail($user_id);

        $user->update([
            'email'         => $validated['email'],
            'name'          => $validated['name'],
            'surname'       => $validated['surname'],
            'faculties_id'  => $validated['faculty_id'],
            'roles_id'      => $validated['role_id'],
            'password'      => isset($validated['new_password']) ? Hash::make($validated['new_password']) : $user->password,
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ]);
    }
}
