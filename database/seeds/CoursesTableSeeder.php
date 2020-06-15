<?php

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{

    public function run()
    {
        $cc = [
            'Osnove programiranja',
            'Engleski jezik',
            'Arhitektura racunara',
            'Sociologija tehnike',
            'Objektno orijentisano programiranje 1',
            'Algoritmi i strukture podataka',
            'Internet mreze',
            'Uvod u softversko inzenjerstvo',
            'Algebra',
            'Matematicka analiza',
            'Objektno orijentisano programiranje 2',
            'Nelinearno programiranje i evolutivni algoritmi',
            'Sistemska programska podrska 1',
            'Specifikacija i modeliranje softvera',
            'Paralelno programiranje',
            'Numericki algoritmi i numericki softver',
            'Baze podataka',
            'Organizacija podataka',
            'Operativni sistemi',
            'Osnovi racunarske tehnike',
            'Statistika',
            'Diskretna matematika',
            'Pisana i govorna komunikacija u tehnici',
            'Internet softverske arhitekture',
            'Veb programiranje',
            'Metodologije razvoja softvera',
            'Programski prevodioci',
            'Osnovi racunarske inteligencije',
            'Softver nadzorno-upravljackih sistema',
            'Interakcija covek racunar',
            'Softverski obrazci i komponente',
            'Konstrukcija i testiranje softvera',
            'Napredne veb tehnologije',
            'Napredne tehnike programiranja',
            'Soft kompjuting',
            'Masinsko ucenje',
            'Bezbednost u sistemima elektronskog poslovanja',
            'XML i veb servisi',
            'Sistemi bazirani na znanju',
            'Strucna praksa',
        ];

        for ($i=0; $i < 10; $i++) {
            $c1 = new Course();
            $c1->name = $cc[$i];
            $c1->start_date = new Carbon('2020-10-01');
            $c1->end_date = new Carbon('2021-02-01');
            $c1->save();
        }
        for ($i=10; $i < 20; $i++) {
            $c1 = new Course();
            $c1->name = $cc[$i];
            $c1->start_date = new Carbon('2021-02-01');
            $c1->end_date = new Carbon('2021-06-01');
            $c1->save();
        }
        for ($i=20; $i < 30; $i++) {
            $c1 = new Course();
            $c1->name = $cc[$i];
            $c1->start_date = new Carbon('2021-10-01');
            $c1->end_date = new Carbon('2022-02-01');
            $c1->save();
        }
        for ($i=30; $i < 40; $i++) {
            $c1 = new Course();
            $c1->name = $cc[$i];
            $c1->start_date = new Carbon('2022-02-01');
            $c1->end_date = new Carbon('2023-06-01');
            $c1->save();
        }
    }
}
