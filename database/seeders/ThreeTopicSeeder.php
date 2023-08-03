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

class ThreeTopicSeeder extends Seeder
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
            'name' => 'preSession',
            'date_time' => now(),
            'no_of_modules' => 4,
            'max_session' => 120,
            'max_buffer' => 30,
            'max_time_expert' => 30,
            'max_time_jigsaw' => 60,
            'status' => 'onReady',
            'is_expert_form' => 0,
            'is_jigsaw_form' => 0,
            'is_new' => 1,
            'is_start' => 0,
            'is_complete' => 0,
        ]);

        Module::factory(4)->create([
            'topic_id' => 1,
        ]);

        Option::factory()->create([
            'topic_id' => 1,
            'group_distribution' => 'random',
            'time_method' => 'even',
            'tm1' => '15',
            'tm2' => '15',
            'tm3' => '15',
            'tm4' => '15',
            'tm5' => '15',
            'tm6' => '15',
        ]);

        Assessment::factory()->create([
            'topic_id' => 1,
            'time' => 10,
            'is_ready_publish' => 1,
            'is_publish' => 0,
            'is_complete' => 0,
        ]);

        Topic::factory()->create([
            'classroom_id' => 1,
            'name' => 'expertSession',
            'date_time' => now(),
            'no_of_modules' => 4,
            'max_session' => 120,
            'max_buffer' => 30,
            'max_time_expert' => 30,
            'max_time_jigsaw' => 60,
            'status' => 'onReady',
            'is_expert_form' => 0,
            'is_jigsaw_form' => 0,
            'is_new' => 1,
            'is_start' => 0,
            'is_complete' => 0,
        ]);

        Module::factory(4)->create([
            'topic_id' => 2,
        ]);

        Option::factory()->create([
            'topic_id' => 2,
            'group_distribution' => 'genderFixed',
            'time_method' => 'even',
            'tm1' => '15',
            'tm2' => '15',
            'tm3' => '15',
            'tm4' => '15',
            'tm5' => '15',
            'tm6' => '15',
        ]);

        Assessment::factory()->create([
            'topic_id' => 2,
            'time' => 10,
            'is_ready_publish' => 1,
            'is_publish' => 0,
            'is_complete' => 0,
        ]);

        Topic::factory()->create([
            'classroom_id' => 1,
            'name' => 'jigsawSession',
            'date_time' => now(),
            'no_of_modules' => 4,
            'max_session' => 120,
            'max_buffer' => 30,
            'max_time_expert' => 30,
            'max_time_jigsaw' => 60,
            'status' => 'onReady',
            'is_expert_form' => 0,
            'is_jigsaw_form' => 0,
            'is_new' => 1,
            'is_start' => 0,
            'is_complete' => 0,
        ]);

        Module::factory(4)->create([
            'topic_id' => 3,
        ]);

        Option::factory()->create([
            'topic_id' => 3,
            'group_distribution' => 'genderMixed',
            'time_method' => 'even',
            'tm1' => '15',
            'tm2' => '15',
            'tm3' => '15',
            'tm4' => '15',
            'tm5' => '15',
            'tm6' => '15',
        ]);

        Assessment::factory()->create([
            'topic_id' => 3,
            'is_ready_publish' => 0,
            'is_publish' => 0,
            'is_complete' => 0,
        ]);

        User::factory()->create([
            'name' => 'STUDENT TEST',
            'email' => 'test@gmail.com',
            'password' => bcrypt('test1234'),
            'type' => 'student',
        ]);

        User::factory(19)->create([
            'password' => bcrypt('test1234')
        ]);

        $classrooms = Classroom::first();

        User::all()->each(function ($user) use ($classrooms) {
            $user->classrooms()->attach(
                $classrooms->pluck('id')->toArray()
            );
        });


        Assessment::find(1)
            ->users()
            ->attach(Topic::find(1)->getStudents()->pluck('id'), [
                'is_finish' => 0,
            ]);

        Assessment::find(2)
            ->users()
            ->attach(Topic::find(2)->getStudents()->pluck('id'), [
                'is_finish' => 0,
            ]);
//        Assessment::find(3)
//            ->users()
//            ->attach(Topic::find(3)->getStudents()->pluck('id'), [
//                'is_finish' => 0,
//            ]);


        $topicModal = Topic::find(1);
        $usersId = $topicModal->getStudents()->pluck('id');
        for ($i = 0; $i < count(Topic::find(1)->getStudents()); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($usersId[$i]);
            $attendance->topic()->associate($topicModal->id);
            $attendance->date = date('Y/m/d');
            if ($usersId[$i] === 2) {
                $attendance->attend_status = 'absent';
            } else {
                $attendance->attend_status = 'present';
            }
            $attendance->save();
        }

        $topicModal = Topic::find(2);
        $usersId = $topicModal->getStudents()->pluck('id');
        for ($i = 0; $i < count(Topic::find(2)->getStudents()); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($usersId[$i]);
            $attendance->topic()->associate($topicModal->id);
            $attendance->date = date('Y/m/d');
            if ($usersId[$i] === 2) {
                $attendance->attend_status = 'absent';
            } else {
                $attendance->attend_status = 'present';
            }
            $attendance->save();
        }

        $topicModal = Topic::find(3);
        $usersId = $topicModal->getStudents()->pluck('id');
        for ($i = 0; $i < count(Topic::find(3)->getStudents()); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($usersId[$i]);
            $attendance->topic()->associate($topicModal->id);
            $attendance->date = date('Y/m/d');
            if ($usersId[$i] === 2) {
                $attendance->attend_status = 'absent';
            } else {
                $attendance->attend_status = 'present';
            }
            $attendance->save();
        }
    }
}
