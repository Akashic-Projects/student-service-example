<?php

use App\Models\UserCourse;
use Illuminate\Database\Seeder;

class UserCoursesTableSeeder extends Seeder
{

    public function run()
    {
        $uc = new UserCourse();
        $uc->user_id = 1;
        $uc->course_id = 1;
        $uc->enrolled = true;
        $uc->grade = 8;
        $uc->rating = 9;
        $uc->save();


        $uc = new UserCourse();
        $uc->user_id = 1;
        $uc->course_id = 5;
        $uc->enrolled = true;
        $uc->grade = 10;
        $uc->rating = 10;
        $uc->save();

        $uc = new UserCourse();
        $uc->user_id = 1;
        $uc->course_id = 6;
        $uc->enrolled = true;
        $uc->grade = 9;
        $uc->rating = 10;
        $uc->save();

        $uc = new UserCourse();
        $uc->user_id = 2;
        $uc->course_id = 3;
        $uc->enrolled = true;
        $uc->grade = 10;
        $uc->rating = 10;
        $uc->save();

        $uc = new UserCourse();
        $uc->user_id = 1;
        $uc->course_id = 9;
        $uc->enrolled = true;
        $uc->grade = 6;
        $uc->rating = 10;
        $uc->save();
    }
}
