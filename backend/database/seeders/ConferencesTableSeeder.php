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
                'conference_date'           => '2022-08-01 15:00:00',
                'submission_deadline'       => '2022-06-01 23:59:59',
                'title'                     => 'Medzinárodná konferencia o umelej inteligencii a strojovom učení',
                'abstract'                  => 'Konferencia sa sústreďuje na najnovšie pokroky v oblasti umelej inteligencie, strojového učenia a ich aplikácií. Témy zahŕňajú hlboké učenie, spracovanie prirodzeného jazyka, robotiku a umeleckú tvorbu poháňanú AI. Podujatie ponúka prednášky od uznávaných odborníkov, praktické workshopy a priestor na networking.',
                'review_assignment_deadline'=> '2022-06-15 23:59:59',
                'review_submission_deadline'=> '2022-06-20 23:59:59',
                'review_publication_date'   => '2022-07-01 23:59:59',
                'is_active'                 => false,
                'location_id'               => University::first()->id,
            ],
            [
                'conference_date'           => '2023-08-01 15:00:00',
                'submission_deadline'       => '2023-06-01 23:59:59',
                'title'                     => 'Digitálna budúcnosť: Konferencia o informačných technológiách a dátovej vede',
                'abstract'                  => 'Táto konferencia je venovaná novým trendom v oblasti analýzy veľkých dát, blockchainu, kybernetickej bezpečnosti a vývoju softvéru. Účastníci môžu očakávať prezentácie výskumných prác, diskusie s lídrami z priemyslu a startupové súťaže. Dôraz je kladený na implementáciu inovácií do praxe.',
                'review_assignment_deadline'=> '2023-06-15 23:59:59',
                'review_submission_deadline'=> '2023-06-20 23:59:59',
                'review_publication_date'   => '2023-07-01 23:59:59',
                'is_active'                 => false,
                'location_id'               => University::first()->id,
            ],
            [
                'conference_date'           => '2024-08-01 15:00:00',
                'submission_deadline'       => '2024-06-01 23:59:59',
                'title'                     => 'AI Summit Slovensko',
                'abstract'                  => 'AI Summit je prestížnym podujatím, ktoré spája vedcov, podnikateľov a študentov s cieľom diskutovať o vplyve umelej inteligencie na spoločnosť, priemysel a vzdelávanie. Hlavné témy zahŕňajú etické aspekty AI, autonómne systémy a integráciu AI do medicíny.',
                'review_assignment_deadline'=> '2024-06-15 23:59:59',
                'review_submission_deadline'=> '2024-06-20 23:59:59',
                'review_publication_date'   => '2024-07-01 23:59:59',
                'is_active'                 => false,
                'location_id'           => University::first()->id,
            ],
            [
                'conference_date'           => '2025-08-01 15:00:00',
                'submission_deadline'       => '2025-06-01 23:59:59',
                'title'                     => 'Konferencia o počítačovej vízii a spracovaní obrazu',
                'abstract'                  => 'Táto konferencia sa zameriava na najnovšie výskumy v oblasti rozpoznávania obrazov, analýzy videí a aplikácie počítačovej vízie v rôznych odvetviach, ako sú autonómne vozidlá a zdravotníctvo. Podujatie obsahuje praktické workshopy a ukážky najnovších technológií. Miesto konania: Nitra.',
                'review_assignment_deadline'=> '2025-06-15 23:59:59',
                'review_submission_deadline'=> '2025-06-20 23:59:59',
                'review_publication_date'   => '2025-07-01 23:59:59',
                'is_active'                 => true,
                'location_id'           => University::first()->id,
            ],
            [
                'conference_date'           => '2025-11-01 15:00:00',
                'submission_deadline'       => '2025-09-01 23:59:59',
                'title'                     => 'Slovenský sympózium o kybernetickej bezpečnosti a AI',
                'abstract'                  => 'Podujatie spája odborníkov na kybernetickú bezpečnosť a vývojárov AI, aby diskutovali o prevencii a odhaľovaní kybernetických hrozieb pomocou umelej inteligencie. Súčasťou programu sú praktické ukážky forenzných analýz a bezpečnostné simulácie.',
                'review_assignment_deadline'=> '2025-09-15 23:59:59',
                'review_submission_deadline'=> '2025-09-20 23:59:59',
                'review_publication_date'   => '2025-10-01 23:59:59',
                'is_active'                 => true,
                'location_id'           => University::first()->id,
            ],
            [
                'conference_date'           => '2026-08-01 15:00:00',
                'submission_deadline'       => '2026-06-01 23:59:59',
                'title'                     => 'Študentská konferencia o umelej inteligencii a informatike',
                'abstract'                  => 'Táto konferencia je určená pre študentov vysokých škôl, ktorí prezentujú svoje výskumné práce a projekty v oblasti informatiky a umelej inteligencie. Podujatie zahŕňa aj mentoringové sekcie, súťaže v programovaní a možnosť získať spätnú väzbu od skúsených odborníkov.',
                'review_assignment_deadline'=> '2026-06-15 23:59:59',
                'review_submission_deadline'=> '2026-06-20 23:59:59',
                'review_publication_date'   => '2026-07-01 23:59:59',
                'is_active'                 => true,
                'location_id'           => University::first()->id,
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($conferences as $conference) {
                Conference::updateOrCreate(
                    [
                        'conference_date'           => $conference['conference_date'],
                        'submission_deadline'       => $conference['submission_deadline'],
                        'title'                     => $conference['title'],
                        'abstract'                  => $conference['abstract'],
                        'review_assignment_deadline'=> $conference['review_assignment_deadline'],
                        'review_submission_deadline'=> $conference['review_submission_deadline'],
                        'review_publication_date'   => $conference['review_publication_date'],
                        'is_active'                 => $conference['is_active'],
                        'location_id'               => $conference['location_id'],
                        'created_at'                => now(),
                        'updated_at'                => now(),
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
