<?php

namespace Database\Seeders;

use App\Models\ArticleStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $article_statuses = [
            [
                'key'   => 'draft',
                'name'  => 'Rozpracovaná',
            ],
            [
                'key'   => 'submitted',
                'name'  => 'Podaná',
            ],
            [
                'key'   => 'under_review',
                'name'  => 'Posudzovaná',
            ],
            [
                'key'   => 'accepted',
                'name'  => 'Prijatá',
            ],
            [
                'key'   => 'returned',
                'name'  => 'Vrátená na doplnenie',
            ],
            [
                'key'   => 'accepted_with_conditions',
                'name'  => 'Prijatá s podmienkou',
            ],
            [
                'key'   => 'rejected',
                'name'  => 'Odmietnutá',
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($article_statuses as $article_status) {
                ArticleStatus::updateOrCreate(
                    [
                        'key'           => $article_status['key'],
                    ],
                    [
                        'name'          => $article_status['name'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed article statuses: ' . $e->getMessage());
        }
    }
}
