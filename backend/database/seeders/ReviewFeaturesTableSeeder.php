<?php

namespace Database\Seeders;

use App\Models\ReviewFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $review_features = [
            [
                'content'   => 'Aktuálnosť a náročnosť práce',
                'is_active' => 1,
            ],
            [
                'content'   => 'Zorientovanie sa študenta v danej problematike predovšetkým analýzou domácej a zahraničnej literatúry',
                'is_active' => 1,
            ],
            [
                'content'   => 'Vhodnosť zvolených metód spracovania riešenej problematiky',
                'is_active' => 1,
            ],
            [
                'content'   => 'Rozsah a úroveň dosiahnutých výsledkov',
                'is_active' => 1,
            ],
            [
                'content'   => 'Analýza a interpretácia výsledkov a formulácia záverov práce',
                'is_active' => 1,
            ],
            [
                'content'   => 'Prehľadnosť a logická štruktúra práce',
                'is_active' => 1,
            ],
            [
                'content'   => 'Formálna, jazyková a štylistická úroveň práce',
                'is_active' => 1,
            ],
            [
                'content'   => 'Práca zodpovedá šablóne určenej pre ŠVK',
                'is_active' => 1,
            ],
            [
                'content'   => 'Chýba názov práce v slovenskom alebo anglickom jazyku',
                'is_active' => 1,
            ],
            [
                'content'   => 'Chýba meno autora alebo školiteľa',
                'is_active' => 1,
            ],
            [
                'content'   => 'Chýba pracovná emailová adresa autora alebo školiteľa',
                'is_active' => 1,
            ],
            [
                'content'   => 'Chýba abstrakt v slovenskom alebo anglickom jazyku',
                'is_active' => 1,
            ],
            [
                'content'   => 'Abstrakt nespĺňa rozsah 100-150 slov',
                'is_active' => 1,
            ],
            [
                'content'   => 'Chýbajú kľúčové slová v slovenskom alebo anglickom jazyku',
                'is_active' => 1,
            ],
            [
                'content'   => 'Chýba „Úvod“, „Výsledky a diskusia“ alebo „Záver“',
                'is_active' => 1,
            ],
            [
                'content'   => 'Nie sú uvedené zdroje a použitá literatúra',
                'is_active' => 1,
            ],
            [
                'content'   => 'V texte chýbajú referencie na zoznam bibliografie',
                'is_active' => 1,
            ],
            [
                'content'   => 'V texte chýbajú referencie na použité obrázky a/alebo tabuľky',
                'is_active' => 1,
            ],
            [
                'content'   => 'Obrázkom a/alebo tabuľkám chýba popis',
                'is_active' => 1,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($review_features as $review_feature) {
                ReviewFeature::updateOrCreate(
                    [
                        'content'       => $review_feature['content'],
                    ],
                    [
                        'is_active'     => $review_feature['is_active'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to seed review features: ' . $e->getMessage());
        }
    }
}
