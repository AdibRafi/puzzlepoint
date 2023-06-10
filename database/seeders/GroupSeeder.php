<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Module;
use App\Models\Option;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $studentNum, int $modules, bool $has_studentTest): void
    {

        //fixed user
        User::factory()->create([
            'name' => 'adib',
            'email' => 'adibtest@gmail.com',
            'password' => bcrypt('test1234'),
            'type' => 'lecturer',
            'wizard_status' => 'done',
            'is_wizard_complete' => 1
        ]);
        Classroom::factory()->create([
            'name' => 'classroomTest',
            'subject_code' => 'codeTest',
            'is_new' => 1,
        ]);

        Topic::factory()->create([
            'classroom_id' => 1,
            'name' => 'topicTest',
            'date_time' => now(),
            'no_of_modules' => $modules,
            'max_time_expert' => 30,
            'max_time_jigsaw' => 60,
            'status' => 'onReady',
            'is_expert_form' => 0,
            'is_jigsaw_form' => 0,
            'is_new' => 1,
            'is_start' => 0,
            'is_complete' => 0,
        ]);

        Assessment::factory()->create([
            'topic_id' => Topic::first()->id,

        ]);

        Option::factory()->create([
            'topic_id' => 1,
            'group_distribution' => 'none',
            'time_method' => 'even',
            'tm1' => '30',
            'tm2' => '30'
        ]);

        Module::factory($modules)->create([
            'topic_id' => 1,
        ]);

        if ($has_studentTest === true) {
            User::factory()->create([
                'name' => 'STUDENT TEST',
                'email' => 'test@gmail.com',
                'password' => bcrypt('test1234'),
                'type' => 'student',
            ]);
        }

        $classrooms = Classroom::all();

        User::all()->each(function ($user) use ($classrooms) {
            $user->classrooms()->attach(
                $classrooms->pluck('id')->toArray()
            );
        });

        User::factory($studentNum)->create();

        User::all()->each(function ($user) use ($classrooms) {
            $user->classrooms()->syncWithoutDetaching(
                $classrooms->pluck('id')->toArray()
            );
        });

        $topicModal = Topic::find(1);
        $usersId = $topicModal->getStudents()->pluck('id');
        for ($i = 0; $i < count(Topic::find(1)->getStudents()); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($usersId[$i]);
            $attendance->topic()->associate($topicModal->id);
            $attendance->date = date('Y/m/d');
            $attendance->attend_status = 'present';
            $attendance->save();
        }
    }
}
