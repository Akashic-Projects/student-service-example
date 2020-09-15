<?php

use App\Models\Authority;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $user1 = new User();
        $user1->name = "admin_user";
        $user1->email = "admin_user@ss.com";
        $user1->password = Hash::make("123456");
        $user1->remember_token = Str::random(10);
        $a = Authority::where('name', 'admin')->first();
        $user1->authority_id = $a->id;
        $user1->save();

        $user2 = new User();
        $user2->name = "akashic_user";
        $user2->email = "akashic_user@ss.com";
        $user2->password = Hash::make("123456");
        $user2->remember_token = Str::random(10);
        $a = Authority::where('name', 'akashic')->first();
        $user2->authority_id = $a->id;
        $user2->save();

        $user3 = new User();
        $user3->name = "sservice_user";
        $user3->email = "sservice_user@ss.com";
        $user3->password = Hash::make("123456");
        $user3->remember_token = Str::random(10);
        $a = Authority::where('name', 'sservice')->first();
        $user3->authority_id = $a->id;
        $user3->save();

        $user4 = new User();
        $user4->name = "student_user1";
        $user4->email = "student_user1@gmail.com";
        $user4->password = Hash::make("123456");
        $user4->remember_token = Str::random(10);
        $a = Authority::where('name', 'student')->first();
        $user4->authority_id = $a->id;
        $user4->save();

        $user5 = new User();
        $user5->name = "student_user2";
        $user5->email = "student_user2@gmail.com";
        $user5->password = Hash::make("123456");
        $user5->remember_token = Str::random(10);
        $a = Authority::where('name', 'student')->first();
        $user5->authority_id = $a->id;
        $user5->save();
    }
}
