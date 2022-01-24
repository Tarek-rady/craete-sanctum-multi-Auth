<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{


    public function run()
    {
        User::create([
            'name' => 'user' ,
            'email'=>'user@yahoo.com',
            'password'=>bcrypt('12345678'),
            'email_verified_at'=>now(),
            'remember_token'=>Str::random(10),
        ]);
    }
}
