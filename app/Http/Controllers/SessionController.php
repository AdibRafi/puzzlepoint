<?php

namespace App\Http\Controllers;

use App\Events\MoveSession;
use App\Events\StudentAttendance;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Topic;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    private function initiateTopicModuleStudentModal($topic_id)
    {
        $topicModal = Topic::find($topic_id);
        $topicModuleModal = $topicModal->with('modules')->first();
        $studentAttendModal = $topicModal->getAttendStudents();
        $studentAbsentModal = $topicModal->getAbsentStudents();

        return [$topicModuleModal, $studentAttendModal, $studentAbsentModal];
    }

    public function lecturerIndexSession(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        return inertia('Session/Index', compact('topicModuleModal', 'studentAttendModal', 'studentAbsentModal'));

    }

    public function lecturerExpertSession(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));


        if ($topicModuleModal->expert_form_group === 0 ||
            $topicModuleModal->jigsaw_form_group === 0) {

            $studentAttendModal = $studentAttendModal->shuffle();
            $numOfModules = $topicModuleModal->no_of_modules;
            $modulesId = $topicModuleModal->modules->pluck('id');

            (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

            if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
                $numOfModules *= 2;
                $totalGroup /= 2;
                $modulesId = $modulesId->merge($modulesId);
            }

            $splitUserIdE = $studentAttendModal->split($totalGroup);
//            dd($splitUserIdE);

//            if ($topicModuleModal->jigsaw_form_group === 0) {
//                for ($i = 0; $i < $totalGroup; $i++) {
//                    if ($splitUserIdJ !== null) {
//                        $group = new Group();
//                        $group->name = 'jigsaw' . $i;
//                        $group->type = 'jigsaw';
//                        $group->topic()->associate($topicModuleModal->id);
//                        $group->save();
//
//                        $group->users()->attach($splitUserIdJ->pop());
//                    }
//                }
//                $topicModuleModal->update(['jigsaw_form_group' => 1]);
//            }

//            dd(count($splitUserIdE[0]));
            if ($topicModuleModal->expert_form_group === 0) {
                for ($j = 0; $j < $numOfModules; $j++) {
                    $group = new Group();
                    $group->name = 'expert' . $j;
                    $group->type = 'expert';
                    $group->topic()->associate($topicModuleModal->id);
                    $group->module()->associate($modulesId[$j]);
                    $group->save();

                    for ($i = 0; $i < count($splitUserIdE); $i++) {
                        if (count($splitUserIdE[$i]) === $numOfModules) {
                            $group->users()->attach($splitUserIdE[$i]->pop());
                        } else {
                            $group->users()->attach($splitUserIdE[$i]->pop());
                            $group->users()->attach($splitUserIdE[$i]->pop());
                        }
                    }
                }
                $topicModuleModal->update(['expert_form_group' => 1]);
            }
        }

        $expertGroupUserModal = $topicModuleModal->groups()->with('users.attendances')
            ->where('type', '=', 'expert')->get();

        broadcast(new MoveSession('goExpert'));

        return inertia('Session/Expert',
            compact('topicModuleModal', 'studentAttendModal', 'studentAbsentModal', 'expertGroupUserModal'));
    }

    public function lecturerJigsawSession(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        $expertGroupModal = $topicModuleModal->groups()->where('type', '=', 'expert')->get();
        $expertUserModal = Topic::find($request->input('topic_id'))->groups()->where('type', '=', 'expert')->with('users')->get()->pluck('users')->flatten();


        $numOfModules = $topicModuleModal->no_of_modules;
        $modulesId = $topicModuleModal->modules->pluck('id');

        (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

        if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
            $numOfModules *= 2;
            $totalGroup /= 2;
            $modulesId = $modulesId->merge($modulesId);
        }
        $splitUser = $expertUserModal->split($totalGroup);
//        dd($expertUserModal);

        //todo: deal with remainder
        if ($topicModuleModal->jigsaw_form_group === 0) {
            for ($j = 0; $j < $totalGroup; $j++) {
                if ($splitUser !== null) {
                    $group = new Group();
                    $group->name = 'jigsaw' . $j;
                    $group->type = 'jigsaw';
                    $group->topic()->associate($topicModuleModal->id);
                    $group->save();
                    for ($k = 0; $k < count($splitUser); $k++) {
                        $group->users()->attach($splitUser[$k]->pop());
                    }
                } else {
                    dd($splitUser[$j]);
                }
            }
            $topicModuleModal->update(['jigsaw_form_group' => 1]);
        }

        $jigsawGroupUserModal = $topicModuleModal->groups()->where('type', '=', 'jigsaw')->with('users')->get();


        broadcast(new MoveSession('goJigsaw'));
        //todo: find a way to get expert -> jigsaw group
        return inertia('Session/Jigsaw', compact('topicModuleModal', 'jigsawGroupUserModal', 'studentAbsentModal'));
    }

    private function initiateStudentSessionModal($topic_id, $groupType)
    {
        $topicModal = Topic::find($topic_id);
        $groupUserModal = Group::whereHas('users', function ($query) use ($topicModal) {
            $query->where('user_id', Auth::id());
            $query->where('topic_id', '=', $topicModal->id);
        })->where('type', '=', $groupType)->with('users')->first();

//        $userModal = Group::find($groupModal->id)->users()->get();
        return [$topicModal, $groupUserModal];
    }

    public function studentSessionIndex(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        //Attendance
        $a = Topic::find($request->input('topic_id'))->attendances()->get();
        $b = Auth::user()->attendances()->get();
        $c = collect();
        $c = $c->merge($a)->merge($b)->duplicates()->values();

        foreach ($c as $value) {
            $value->update([
                'attend_status' => 'present'
            ]);
        }

        broadcast(new StudentAttendance('done tukar'));

        return inertia('Session/Index', compact('topicModuleModal'));
    }

    public function studentExpertSession(Request $request) //topic_id
    {
        list($topicModal, $groupUserModal) =
            $this->initiateStudentSessionModal($request->input('topic_id'), 'expert');

        $moduleModal = $groupUserModal->module()->first();

        return inertia('Session/Student/Expert',
            compact('topicModal', 'groupUserModal', 'moduleModal'));
    }

    public function studentJigsawSession(Request $request)
    {
        list($topicModal, $groupUserModal) =
            $this->initiateStudentSessionModal($request->input('topic_id'), 'jigsaw');

        return inertia('Session/Student/Jigsaw',
            compact('topicModal', 'groupUserModal'));
    }

    private function initiateModal($topic_id, $groupType)
    {
        $topicModal = Topic::find($topic_id);
        $groupModal = Group::whereHas('users', function ($query) use ($groupType, $topicModal) {
            $query->where('user_id', Auth::id());
            $query->where('topic_id', '=', $topicModal->id);
        })->where('type', '=', $groupType)->first();
        $userModal = Group::find($groupModal->id)->users()->get();
        return [$topicModal, $groupModal, $userModal];

    }

    public function AstudentExpert(Request $request) //topic_id
    {
        list($topicModal, $groupModal, $userModal) = $this->initiateModal($request->input('topic_id'), 'expert');

//        dd($groupModal);

//        dd($groupModal);
        return inertia('Student/Session/ExpertSession', compact('topicModal', 'groupModal', 'userModal'));

    }

    public function AstudentJigsaw(Request $request) //topic_id
    {
        list($topicModal, $groupModal, $userModal) = $this->initiateModal($request->input('topic_id'), 'jigsaw');

        return inertia('Student/Session/JigsawSession', compact('topicModal', 'groupModal', 'userModal'));
    }

    public function AlecturerExpert(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $groupUserAttendModal = $topicModal->groups()->with('users.attendances')->where('type', '=', 'expert')->get();
        $absentStudentModal = Topic::find($request->input('topic_id'))->getAbsentStudents();

        return inertia('Lecturer/Session/ExpertSession', compact('topicModal', 'groupUserAttendModal', 'absentStudentModal'));
    }

    public function AlecturerJigsaw(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $groupUserAttendModal = $topicModal->groups()->with('users.attendances')->where('type', '=', 'jigsaw')->get();
        $absentStudentModal = Topic::find($request->input('topic_id'))->getAbsentStudents();

        return inertia('Lecturer/Session/JigsawSession', compact('topicModal', 'groupUserAttendModal', 'absentStudentModal'));
    }

    public function sendExpertPusher(Request $request) //topic_id
    {
        $a = Topic::find($request->input('topic_id'))->attendances()->get();
        $b = Auth::user()->attendances()->get();
        $c = collect();
        $c = $c->merge($a)->merge($b)->duplicates()->values();

        foreach ($c as $value) {
            $value->update([
                'attend_status' => 'present'
            ]);
        }
//        dd('nice');

        broadcast(new StudentAttendance('done tukar'));
    }

//    public function fetchExpertAbsentStudent(Request $request) //topic_id
//    {
////        dd($request->all());
//        $absentStudentModal = Topic::find($request->input('topic_id'))->getAbsentStudents();
////        $topic_id = $request->input('topic_id');
////        return redirect()->route('lecturer.jigsaw',compact());
////            ->route('lecturer.expert',compact('topic_id'));
////        return Topic::find($request->input('topic_id'))->getAbsentStudents();
//    }
//
//    public function fetchExpertPusher()
//    {
//        return Topic::find(1)->getAbsentStudents()->first();
////            Topic::find(1)->attendances()->where('attend_status','=','absent')->get();
//    }
}
