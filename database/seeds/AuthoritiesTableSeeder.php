<?php

use App\Models\Authority;
use Illuminate\Database\Seeder;

class AuthoritiesTableSeeder extends Seeder
{

    public function run()
    {
        $super_user = new Authority();
        $super_user->name = "admin";
        $super_user->save();

        $akashic = new Authority();
        $akashic->name = "akashic";
        $akashic->save();

        $sservice = new Authority();
        $sservice->name = "sservice";
        $sservice->save();

        $student = new Authority();
        $student->name = "student";
        $student->save();
    }
}
