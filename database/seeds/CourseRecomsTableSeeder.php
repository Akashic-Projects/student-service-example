<?php

use App\Models\CourseRecom;
use Illuminate\Database\Seeder;

class CourseRecomsTableSeeder extends Seeder
{

    public function run()
    {
        $uc = new CourseRecom();
        $uc->user_id = 2;
        $uc->course_id = 1;
        $uc->save();


        $uc = new CourseRecom();
        $uc->user_id = 2;
        $uc->course_id = 5;
        $uc->save();

        $uc = new CourseRecom();
        $uc->user_id = 2;
        $uc->course_id = 6;
        $uc->save();

        $uc = new CourseRecom();
        $uc->user_id = 2;
        $uc->course_id = 3;
        $uc->save();

        $uc = new CourseRecom();
        $uc->user_id = 2;
        $uc->course_id = 9;
        $uc->save();
    }
}
