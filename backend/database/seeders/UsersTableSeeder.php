<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email'         => 'super_admin@example.com',
                'password'      => Hash::make('superadmin123'),
                'name'          => 'Super',
                'surname'       => 'Admin',
                'roles_id'      => Role::where('key', 'super_admin')->first()->id,
                'faculties_id'  => Faculty::first()->id,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                User::updateOrCreate(
                    [
                        'email'         => $user['email'],
                    ],
                    [
                        'password'      => $user['password'],
                        'name'          => $user['name'],
                        'surname'       => $user['surname'],
                        'roles_id'      => $user['roles_id'],
                        'faculties_id'  => $user['faculties_id'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed users: ' . $e->getMessage());
        }
    }
}
