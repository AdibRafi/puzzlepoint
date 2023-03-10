<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Material;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\View\Factory;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //fixed user
        User::factory()->create([
            'name' => 'adib',
            'email' => 'adibtest@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('test1234'),
            'bio' => 'fakeBioForAdib',
            'type' => 'lecturer',
            'remember_token' => Str::random(10),
        ]);
        User::factory()->create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('test1234'),
            'bio' => 'fakeBioForTest',
            'type' => 'lecturer',
            'remember_token' => Str::random(10),
        ]);


        Classroom::factory(3)->create();
        $classrooms = Classroom::all();

        //attach user-classroom pivot
        User::all()->each(function ($user) use ($classrooms) {
            $user->classrooms()->attach(
                $classrooms->random(rand(1, $classrooms->count()))->pluck('id')->toArray()
            );
        });

        Group::factory(5)->create();
        $groups = Group::all();

        User::all()->each(function ($user) use ($groups) {
            $user->groups()->attach(
                $groups->random(rand(1, $groups->count()))->pluck('id')->toArray()
            );
        });

        Topic::factory(5)->create();
        Assessment::factory(5)->create();
        $assessments = Assessment::all();

        User::all()->each(function ($user) use ($assessments) {
            $user->assessments()->attach(
                $assessments->random(rand(1,$assessments->count()))->pluck('id')->toArray()
            );
        });

        Module::factory(5)->create();
        Material::factory(5)->create();
        Attendance::factory(5)->create();
        Question::factory(5)->create();
        Answer::factory(5)->create();

//        $materials = Material::all();

//        Material::all()->asso


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
