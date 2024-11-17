<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UniversitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            [
                'name'          => 'Univerzita Konštantína Filozofa v Nitre',
                'address'       => 'Trieda Andreja Hlinku 1',
                'city'          => 'Nitra',
                'postal_code'   => '949 01',
                'country'       => 'Slovenská republika',
            ],
            [
                'name'          => 'Univerzita Mateja Bela v Banskej Bystrici',
                'address'       => 'Národná ulica 12',
                'city'          => 'Banská Bystrica',
                'postal_code'   => '974 01',
                'country'       => 'Slovenská republika',
            ],
            [
                'name'          => 'Univerzita sv. Cyrila a Metoda v Trnave',
                'address'       => 'Námestie Jozefa Herdu 2',
                'city'          => 'Trnava',
                'postal_code'   => '917 01',
                'country'       => 'Slovenská republika',
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($universities as $university) {
                University::updateOrCreate(
                    [
                        'name'          => $university['name'],
                    ],
                    [
                        'address'       => $university['address'],
                        'city'          => $university['city'],
                        'postal_code'   => $university['postal_code'],
                        'country'       => $university['country'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed universities: ' . $e->getMessage());
        }
    }
}
