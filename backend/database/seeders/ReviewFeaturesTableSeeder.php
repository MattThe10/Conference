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
                'content'       => 'Aktuálnosť a náročnosť práce',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],
            [
                'content'       => 'Zorientovanie sa študenta v danej problematike predovšetkým analýzou domácej a zahraničnej literatúry',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Vhodnosť zvolených metód spracovania riešenej problematiky',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],
            [
                'content'       => 'Rozsah a úroveň dosiahnutých výsledkov',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Analýza a interpretácia výsledkov a formulácia záverov práce',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Prehľadnosť a logická štruktúra práce',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Formálna, jazyková a štylistická úroveň práce',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Práca zodpovedá šablóne určenej pre ŠVK',
                'rating_type'   => 1, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Chýba názov práce v slovenskom alebo anglickom jazyku',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Chýba meno autora alebo školiteľa',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Chýba pracovná emailová adresa autora alebo školiteľa',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Chýba abstrakt v slovenskom alebo anglickom jazyku',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Abstrakt nespĺňa rozsah 100-150 slov',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Chýbajú kľúčové slová v slovenskom alebo anglickom jazyku',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Chýba „Úvod“, „Výsledky a diskusia“ alebo „Záver“',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Nie sú uvedené zdroje a použitá literatúra',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'V texte chýbajú referencie na zoznam bibliografie',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'V texte chýbajú referencie na použité obrázky a/alebo tabuľky',
                'rating_type'   => 0, 
                'is_active'     => 1,
            ],  
            [   
                'content'       => 'Obrázkom a/alebo tabuľkám chýba popis',
                'rating_type'   => 0, 
                'is_active'     => 1,
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
                        'rating_type'   => $review_feature['rating_type'],
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
