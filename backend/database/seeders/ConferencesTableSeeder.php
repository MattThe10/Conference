<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\University;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conferences = [
            [
                'start_year'            => 2021,
                'end_year'              => 2022,
                'conference_date'       => '2022-08-01 15:00:00',
                'submission_deadline'   => '2022-07-01 20:00:00',
                'location_id'           => University::first()->id,
            ],
            [
                'start_year'            => 2022,
                'end_year'              => 2023,
                'conference_date'       => '2023-08-01 15:00:00',
                'submission_deadline'   => '2023-07-01 20:00:00',
                'location_id'           => University::first()->id,
            ],
            [
                'start_year'            => 2023,
                'end_year'              => 2024,
                'conference_date'       => '2024-08-01 15:00:00',
                'submission_deadline'   => '2024-07-01 20:00:00',
                'location_id'           => University::first()->id,
            ],
            [
                'start_year'            => 2024,
                'end_year'              => 2025,
                'conference_date'       => '2025-08-01 15:00:00',
                'submission_deadline'   => '2025-07-01 20:00:00',
                'location_id'           => University::first()->id,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($conferences as $conference) {
                Conference::updateOrCreate(
                    [
                        'start_year'            => $conference['start_year'],
                        'end_year'              => $conference['end_year'],
                    ],
                    [
                        'conference_date'       => $conference['conference_date'],
                        'submission_deadline'   => $conference['submission_deadline'],
                        'location_id'           => $conference['location_id'],
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed conferences: ' . $e->getMessage());
        }
    }
}
