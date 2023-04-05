<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Module;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //fixed user
        User::factory()->create([
            'name' => 'adib',
            'email' => 'adibtest@gmail.com',
            'password' => bcrypt('test1234'),
            'type' => 'lecturer',
        ]);
        Classroom::factory()->create([
            'name' => 'classroomTest',
            'subject_code' => 'codeTest'
        ]);

        Topic::factory()->create([
            'classroom_id' => 1,
            'name' => 'topicTest',
            'date_time' => now(),
            'no_of_modules' => 2,
            'max_time_expert' => 30,
            'max_time_jigsaw' => 60,
            'status' => 'onOption',
        ]);

        Module::factory(2)->create([
            'topic_id' => 1,
            'name' => 'moduleTest'
        ]);

        User::factory()->create([
            'name' => 'STUDENT TEST',
            'email' => 'test@gmail.com',
            'password' => bcrypt('test1234'),
            'type' => 'student',
        ]);

        $classrooms = Classroom::all();

        User::all()->each(function ($user) use ($classrooms) {
            $user->classrooms()->attach(
                $classrooms->pluck('id')->toArray()
            );
        });

        User::factory(19)->create();

        User::all()->each(function ($user) use ($classrooms) {
            $user->classrooms()->syncWithoutDetaching(
                $classrooms->pluck('id')->toArray()
            );
        });
    }
}
