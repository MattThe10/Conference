<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'key'   => 'super_admin',
                'name'  => 'Super admin',
            ],
            [
                'key'   => 'admin',
                'name'  => 'Admin',
            ],
            [
                'key'   => 'reviewer',
                'name'  => 'Reviewer',
            ],
            [
                'key'   => 'student',
                'name'  => 'Student',
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($roles as $role) {
                Role::updateOrCreate(
                    [
                        'key'           => $role['key'],
                    ],
                    [
                        'name'          => $role['name'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed roles: ' . $e->getMessage());
        }
    }
}
