<?php

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            'Programski jezici niskog nivoa',
            'Programski prevodioci',
            'Objektno orijentisano programiranje',
            'Veb programiranje',
            'Napredni algoritmi',
            'Matematika',
            'Matematicke metode u programiranju',
            'Modeliranje softvera',
            'Paralelno programiranje',
            'Numericko programiranje',
            'Baze podataka',
            'Operativni sistemi',
            'Pisanje naucnog rada',
            'Razvoj softvera',
            'Softver u proizvodnim industrijama',
            'Testiranje softvera',
            'Napredne tehnike programiranja',
            'Bezbednost softvera',
            'Veb servisi',
        ];

        for ($i=0; $i < 19; $i++) {
            $s = new Subject();
            $s->name = $subjects[$i];
            $s->save();
        }
    }
}
