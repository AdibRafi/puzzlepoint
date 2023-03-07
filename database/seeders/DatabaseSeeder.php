<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'adib',
            'email' => 'adib@gmail.com',
            'password' => '$2y$10$uzCgP.kRLWPHDgA.YcBtR.NkWeu7NB7BxF2jKh1n/icS/bAgukVC2',
        ]);

        Classroom::factory(3)->create();

//        Question::factory(3)->create();
//        Answer::factory(12)->create();



        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
