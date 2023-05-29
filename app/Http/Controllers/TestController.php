<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Material;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Collection;

class TestController extends Controller
{
    public function index()
    {
//        $data = auth()->user()->attendances()->get();
        $data = auth()->user();

        return inertia('Test', compact('data'));
    }

    public function store(Request $request) //topic_id / groupMethod / timeMethod / tm{}
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $modulesId = $topicModal->modules()->pluck('id');
        $numOfModules = $topicModal->no_of_modules;
        //distribution logic
        $usersId = $topicModal->getStudents()->pluck('id')->shuffle();
//        dd(count($usersId));
//        dd($topicModal->id);
//        dd($modulesId);


        //START JG logic


        (int)$totalGroup = floor(count($usersId) / $numOfModules);
        if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
            $numOfModules *= 2;
            $totalGroup /= 2;
            $modulesId = $modulesId->merge($modulesId);
        }
        //todo: check if remainder need (because using pop())
        $totalRemainder = count($usersId) % $numOfModules;
//        if ($totalRemainder !== 0) {
//            $remainderIdJ = $usersId->take($totalRemainder);
//            $remainderIdE = clone $remainderIdJ;
//        } else {
//            $remainderIdJ = null;
//            $remainderIdE = null;
//        }

        //CHECK NUMBER USER AND MODULE ID BEFORE EXECUTE
        $splitUserIdJ = $usersId->split($totalGroup);
        $splitUserIdE = clone $splitUserIdJ;


        for ($i = 0; $i < $totalGroup; $i++) {
            $group = new Group();
            $group->name = 'jigsaw' . $i;
            $group->type = 'jigsaw';
            $group->topic()->associate($topicModal->id);
            $group->save();

            if ($splitUserIdJ !== null) {
                $group->users()->attach($splitUserIdJ->pop());
            }
//            if ($remainderIdJ !== null) {
//                $group->users()->attach($remainderIdJ->pop());
//            }
        }

        for ($i = 0; $i < $numOfModules; $i++) {
            $group = new Group();
            $group->name = 'expert' . $i;
            $group->type = 'expert';
            $group->topic()->associate($topicModal->id);
            $group->module()->associate($modulesId[$i]);
            $group->save();
            for ($j = 0; $j < count($splitUserIdE); $j++) {
                if ($splitUserIdE !== null) {
                    $group->users()->attach($splitUserIdE[$j]->pop());
                }
            }
//            if ($remainderIdE !== null) {
//                $group->users()->attach($remainderIdE->pop());
//            }
        }
        // FOR TESTING ATTENDANCE PURPOSE

        for ($i = 0; $i < count(Topic::find(1)->getStudents()); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($usersId[$i]);
            $attendance->topic()->associate($topicModal->id);
            $attendance->date = date('Y/m/d');
            $attendance->attend_status = 'present';
            $attendance->save();
        }

        $r = User::find(1)->groups()->pluck('id');
        dd($r);
//        dd(Group::find(10)->modules()->wherePivot('group_type', '=', 'expert')->get());
//        dd(Group::find(80)->users()->pluck('name'));

        dd($topicModal->modules()->pluck('id'));


        dd($usersId);
        return redirect()->route('test.index');
    }

    public function displayGroup(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $expertGroupUserModal = $topicModal->groups()->with('users.attendances')->where('type', '=', 'expert')->get();
        $jigsawGroupUserModal = $topicModal->groups()->with('users.attendances')->where('type', '=', 'jigsaw')->get();
        $absentStudentModal = $topicModal->getAbsentStudents();


        return inertia('DisplayGroup', compact('topicModal', 'expertGroupUserModal', 'absentStudentModal', 'jigsawGroupUserModal'));
    }

    public function displayGroupModified(Request $request)//topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $absentStudentModal = $topicModal->getAbsentStudents();

        $groupModalWithAbsentStudent = $topicModal->groups()
            ->where('type', '=', 'jigsaw')
            ->whereHas('users', function ($query) {
                $query->whereHas('attendances', function ($query) {
                    $query->where('attend_status', '=', 'absent');
                });
            })->get();


        $groupModal = $topicModal->groups()
            ->where('type', '=', 'jigsaw')->get();

        $groupModal = $groupModal->reject(function ($value, $key) use ($groupModalWithAbsentStudent) {
            return $groupModalWithAbsentStudent->contains($value);
        })->flatten();

        $studentWithAbsentModal = $groupModalWithAbsentStudent->pluck('users')->flatten();

        for ($i = 0; $i < count($studentWithAbsentModal); $i++) {
            $group = Group::find($groupModal[$i]->id);
            $group->users()->attach($studentWithAbsentModal[$i]);
        }

//        foreach ($studentWithAbsentModal as $value) {
//            $groupId = $groupModal->random()->id;
//            $group = Group::find($groupId);
//            $group->users()->attach($value);
//        }
        Group::destroy($groupModalWithAbsentStudent);


        dd($groupModal);
        dd($groupModalWithAbsentStudent);
        dd($absentStudentModal);



    }
}
