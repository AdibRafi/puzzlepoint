<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Module;
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
        Classroom::factory(3)->create();
        Group::factory(3)->create();
        Module::factory(3)->create();

//        foreach (Classroom::all() as $classroom) {
//            $users = User::inRandomOrder()->take(rand(1, 3))->pluck('id');
//            $classroom->user()->attach($users);
//        }

//        Question::factory(3)->create();
//        Answer::factory(12)->create();



        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
