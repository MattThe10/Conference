<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            [
                'name'              => 'Fakulta prírodnych vied a informatiky',
                'address'           => 'Trieda Andreja Hlinku 1',
                'city'              => 'Nitra',
                'postal_code'       => '949 01',
                'country'           => 'Slovenská republika',
                'universities_id'   => University::where('name', 'Univerzita Konštantína Filozofa v Nitre')->first()->id,
            ],
            [
                'name'              => 'Fakulta prírodných vied',
                'address'           => 'Tajovského 40',
                'city'              => 'Banská Bystrica',
                'postal_code'       => '974 01',
                'country'           => 'Slovenská republika',
                'universities_id'   => University::where('name', 'Univerzita Mateja Bela v Banskej Bystrici')->first()->id,
            ],
            [
                'name'              => 'Fakulta prírodných vied',
                'address'           => 'Námestie Jozefa Herdu 2',
                'city'              => 'Trnava',
                'postal_code'       => '917 01',
                'country'           => 'Slovenská republika',
                'universities_id'   => University::where('name', 'Univerzita sv. Cyrila a Metoda v Trnave')->first()->id,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($faculties as $faculty) {
                Faculty::updateOrCreate(
                    [
                        'name'              => $faculty['name'],
                        'universities_id'   => $faculty['universities_id'],
                    ],
                    [
                        'address'           => $faculty['address'],
                        'city'              => $faculty['city'],
                        'postal_code'       => $faculty['postal_code'],
                        'country'           => $faculty['country'],
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed faculties: ' . $e->getMessage());
        }
    }
}
