<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'iso_code'  => 'SK',
                'name'      => 'Slovenská republika',
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($countries as $country) {
                Country::updateOrCreate(
                    [
                        'iso_code'  => $country['iso_code'],
                    ],
                    [
                        'name'      => $country['name'],
                    ]
                );
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to seed countries table: ' . $e->getMessage());
        }
    }
}
