<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Faculty;
use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Seeder for universities table
        $universities = [
            [
                'name'              => 'Univerzita Konštantína Filozofa v Nitre',
                'abbreviation'      => 'UKF',
                'street_address'    => 'Tr. A. Hlinku 1',
                'city'              => 'Nitra',
                'district'          => 'Nitra',
                'region'            => 'Nitriansky kraj',
                'postal_code'       => '949 01',
                'country_id'        => Country::where('iso_code', 'SK')->first()->id,
            ],
            [
                'name'              => 'Univerzita Mateja Bela v Banskej Bystrici',
                'abbreviation'      => 'UMB',
                'street_address'    => 'Národná 12',
                'city'              => 'Banská Bystrica',
                'district'          => 'Banská Bystrica',
                'region'            => 'Banskobystrický kraj',
                'postal_code'       => '974 01',
                'country_id'        => Country::where('iso_code', 'SK')->first()->id,
            ],
            [
                'name'              => 'Univerzita sv. Cyrila a Metoda v Trnave',
                'abbreviation'      => 'UCM',
                'street_address'    => 'Námestie J. Herdu 2',
                'city'              => 'Trnava',
                'district'          => 'Trnava',
                'region'            => 'Trnavský kraj',
                'postal_code'       => '917 01',
                'country_id'        => Country::where('iso_code', 'SK')->first()->id,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($universities as $university) {
                University::updateOrCreate(
                    [
                        'name'              => $university['name'],
                        'abbreviation'      => $university['abbreviation'],
                    ],
                    [
                        'street_address'    => $university['street_address'],
                        'city'              => $university['city'],
                        'district'          => $university['district'],
                        'region'            => $university['region'],
                        'postal_code'       => $university['postal_code'],
                        'country_id'        => $university['country_id'],
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to seed universities table: ' . $e->getMessage());
        }

        // Seeder for faculties table
        $faculties = [
            [
                'name'              => 'Fakulty prírodných vied a informatiky',
                'street_address'    => 'Tr. A. Hlinku 1',
                'city'              => 'Nitra',
                'district'          => 'Nitra',
                'region'            => 'Nitriansky kraj',
                'postal_code'       => '949 01',
                'country_id'        => Country::where('iso_code', 'SK')->first()->id,
                'university_id'     => University::where('abbreviation', 'UKF')->first()->id,
            ],
            [
                'name'              => 'Fakulta prírodných vied',
                'street_address'    => 'Tajovského 40',
                'city'              => 'Banská Bystrica',
                'district'          => 'Banská Bystrica',
                'region'            => 'Banskobystrický kraj',
                'postal_code'       => '974 01',
                'country_id'        => Country::where('iso_code', 'SK')->first()->id,
                'university_id'     => University::where('abbreviation', 'UMB')->first()->id,
            ],
            [
                'name'              => 'Fakulta prírodných vied',
                'street_address'    => 'Námestie J. Herdu 2',
                'city'              => 'Trnava',
                'district'          => 'Trnava',
                'region'            => 'Trnavský kraj',
                'postal_code'       => '917 01',
                'country_id'        => Country::where('iso_code', 'SK')->first()->id,
                'university_id'     => University::where('abbreviation', 'UCM')->first()->id,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($faculties as $faculty) {
                Faculty::updateOrCreate(
                    [
                        'name'              => $faculty['name'],
                        'university_id'     => $faculty['university_id'],
                    ],
                    [
                        'street_address'    => $faculty['street_address'],
                        'city'              => $faculty['city'],
                        'district'          => $faculty['district'],
                        'region'            => $faculty['region'],
                        'postal_code'       => $faculty['postal_code'],
                        'country_id'        => $faculty['country_id'],
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to seed faculties table: ' . $e->getMessage());
        }
    }
}
