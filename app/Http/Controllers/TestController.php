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
use App\Notifications\TwoFactorVerification;
use Auth;
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

    public function store(Request $request)
    {
        $topicModuleModal = Topic::find(1)->load('modules');
        $studentAttendModal = $topicModuleModal->getAttendStudents();
        $studentAbsentModal = $topicModuleModal->getAbsentStudents();

        if ($topicModuleModal->is_expert_form === 0) {

            //todo: PRIORITY, distribution thing
            //todo: PRIORITY, do group distribute >= 28 student
            $studentAttendModal = $studentAttendModal->shuffle();
            $numOfModules = $topicModuleModal->no_of_modules;
            $modulesId = $topicModuleModal->modules->pluck('id');

            (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

            if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
                $numOfModules *= 2;
//                $totalGroup /= 2;
                $modulesId = $modulesId->merge($modulesId);
            }

            $splitUser = $studentAttendModal->split($numOfModules);

//            dd(count($splitUser[0]) >= 7);
            if (count($splitUser[0]) >= 7) {
                $numOfModules *= 2;
                $modulesId = $modulesId->merge($modulesId)->sort();
                $splitUser = $studentAttendModal->split($numOfModules);
            }

            for ($j = 0; $j < $numOfModules; $j++) {
                $group = new Group();
                $group->name = 'expert' . $j;
                $group->type = 'expert';
                $group->topic()->associate($topicModuleModal->id);
                $group->module()->associate($modulesId[$j]);
                $group->save();

                $group->users()->attach($splitUser->pop());
                $topicModuleModal->update(['is_expert_form' => 1]);
            }
        }
        $expertGroupUserModal = $topicModuleModal->groups()->with('users.attendances')
            ->where('type', '=', 'expert')->get();

        $expertGroupModal = $topicModuleModal->groups()->where('type', '=', 'expert')->with('users')->withCount('users')->get();
        $expertUserModal = Topic::find($request->input('topic_id'))->groups()->where('type', '=', 'expert')->with('users')->get()->pluck('users')->flatten();

        $remainderUser = collect();
        $lessGroup = $expertGroupModal->min('users_count');
        for ($i = 0; $i < count($expertGroupModal); $i++) {
            if ($expertGroupModal[$i]->users_count > $lessGroup) {
                $user = $expertGroupModal[$i]->users()->get()->pop();
                $remainderUser->push($user);
                $expertUserModal = $expertUserModal->reject(function ($value, $key) use ($user) {
                    return $value->id == $user->id;
                });
            }
        }

        $numOfModules = $topicModuleModal->no_of_modules;
        $modulesId = $topicModuleModal->modules->pluck('id');

        (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

        if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
            $numOfModules *= 2;
            $totalGroup /= 2;
            $modulesId = $modulesId->merge($modulesId);
        }
        $splitUser = $expertUserModal->split($numOfModules);

        if ($topicModuleModal->is_jigsaw_form === 0) {
            for ($j = 0; $j < $totalGroup; $j++) {
                if ($splitUser !== null) {
                    $group = new Group();
                    $group->name = 'jigsaw' . $j;
                    $group->type = 'jigsaw';
                    $group->topic()->associate($topicModuleModal->id);
                    $group->save();
                    for ($k = 0; $k < $numOfModules; $k++) {
                        $group->users()->attach($splitUser[$k]->pop());
                    }
                    if ($remainderUser !== null) {
                        $group->users()->attach($remainderUser->pop());
                    }
                }
            }
            $topicModuleModal->update(['is_jigsaw_form' => 1]);
        }

        $jigsawGroupUserModal = $topicModuleModal->groups()->where('type', '=', 'jigsaw')->with('users')->get();


        $topic_id = 1;
        return redirect()->route('display.group',compact('topic_id'))
            ->with('alertMessage', 'Done create Group');
    }

    public function displayGroup(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $expertGroupUserModal = $topicModal->groups()
            ->with('users.attendances')
            ->with('module')
            ->where('type', '=', 'expert')
            ->get();
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

    public function smsTest()
    {
        Auth::user()->notify(new TwoFactorVerification());
    }
}
