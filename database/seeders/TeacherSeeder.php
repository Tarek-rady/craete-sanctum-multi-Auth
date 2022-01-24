<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{


    public function run()
    {
        DB::table('teachers')->delete();

        $faker = Factory::create();

        $teacher = Teacher::create([
            'name_ar' => 'طارق' ,
            'name_en' => 'tarek' ,
            'email' => 'tarek@yahoo.com' ,
            'mobile' => '01067422197'
        ]);

        for ($i=0; $i <20 ; $i++) {
            $teacher = Teacher::create([
                'name_ar' => $faker->sentence(1 , true) ,
                'name_en' => 'tarek' ,
                'email' => $faker->unique()->email() ,
                'mobile' => '01067422197'
            ]);
        }
    }
}
