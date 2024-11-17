<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UniversitiesTableSeeder::class,
            FacultiesTableSeeder::class,
            ArticleStatusesTableSeeder::class,
            ReviewFeaturesTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}
