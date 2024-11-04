<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'key'   => 'super_admin',
                'name'  => 'Super administrátor',
            ],
            [
                'key'   => 'admin',
                'name'  => 'Administrátor',
            ],
            [
                'key'   => 'reviewer',
                'name'  => 'Recenzent',
            ],
            [
                'key'   => 'student',
                'name'  => 'Študent',
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($roles as $role) {
                Role::updateOrCreate(
                    [
                        'key'   => $role['key'],
                    ],
                    [
                        'name'  => $role['name'],
                    ]
                );
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to seed roles table: ' . $e->getMessage());
        }
    }
}