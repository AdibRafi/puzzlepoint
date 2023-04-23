<?php

namespace App\Http\Controllers;

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

    public function index(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        return inertia('Session/Index', compact('topicModuleModal', 'studentAttendModal', 'studentAbsentModal'));

    }

    public function expertSession(Request $request) //topic_id
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

            $splitUserIdJ = $studentAttendModal->split($totalGroup);
            $splitUserIdE = clone $splitUserIdJ;

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

            if ($topicModuleModal->expert_form_group === 0) {
                for ($j = 0; $j < $numOfModules; $j++) {
                    if ($splitUserIdE !== null) {
                        $group = new Group();
                        $group->name = 'expert' . $j;
                        $group->type = 'expert';
                        $group->topic()->associate($topicModuleModal->id);
                        $group->module()->associate($modulesId[$j]);
                        $group->save();
                        for ($k = 0; $k < count($splitUserIdE); $k++) {
                            $group->users()->attach($splitUserIdE[$k]->pop());
                        }
                    }
                }
                $topicModuleModal->update(['expert_form_group' => 1]);
            }
        }

        $expertGroupUserModal = $topicModuleModal->groups()->with('users.attendances')
            ->where('type', '=', 'expert')->get();


        return inertia('Session/Expert',
            compact('topicModuleModal', 'studentAttendModal', 'studentAbsentModal', 'expertGroupUserModal'));
    }

    public function jigsawSession(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        $expertGroupModal = $topicModuleModal->groups()->where('type', '=', 'expert')->get();
        $expertUserModal = Topic::find($request->input('topic_id'))->groups()->where('type', '=', 'expert')->with('users')->get()->pluck('users')->flatten();

        $splitUser = $expertUserModal->split(count($expertGroupModal));
//        dd(count($splitUser[0]));
        $loopForSplitUser = count($splitUser[0]);

        //todo: deal with remainder
        if ($topicModuleModal->jigsaw_form_group === 0) {
            for ($j = 0; $j < $loopForSplitUser; $j++) {
                if ($splitUser[0] !== null) {
                    $group = new Group();
                    $group->name = 'jigsaw' . $j;
                    $group->type = 'jigsaw';
                    $group->topic()->associate($topicModuleModal->id);
                    $group->save();
                    for ($k = 0; $k < count($splitUser); $k++) {
                        $group->users()->attach($splitUser[$k]->pop());
                    }
                }
                else{
                    dd($splitUser[$j]);
                }
            }
            $topicModuleModal->update(['jigsaw_form_group' => 1]);
        }

        $jigsawGroupUserModal = $topicModuleModal->groups()->where('type', '=', 'jigsaw')->with('users')->get();


        //todo: find a way to get expert -> jigsaw group
        return inertia('Session/Jigsaw', compact('topicModuleModal', 'jigsawGroupUserModal'));
    }

    private function initiateModal($topic_id, $groupType)
    {
        $topicModal = Topic::find($topic_id);
        $groupModal = Group::whereHas('users', function (Builder $query) use ($groupType, $topicModal) {
            $query->where('user_id', Auth::id());
            $query->where('type', '=', $groupType);
            $query->where('topic_id', '=', $topicModal->id);
        })->first();
        $userModal = Group::find($groupModal->id)->users()->get();
        return [$topicModal, $groupModal, $userModal];

    }

    public function studentExpert(Request $request) //topic_id
    {
        list($topicModal, $groupModal, $userModal) = $this->initiateModal($request->input('topic_id'), 'expert');

//        dd($groupModal);

//        dd($groupModal);
        return inertia('Student/Session/ExpertSession', compact('topicModal', 'groupModal', 'userModal'));

    }

    public function studentJigsaw(Request $request) //topic_id
    {
        list($topicModal, $groupModal, $userModal) = $this->initiateModal($request->input('topic_id'), 'jigsaw');

        return inertia('Student/Session/JigsawSession', compact('topicModal', 'groupModal', 'userModal'));
    }

    public function lecturerExpert(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $groupUserAttendModal = $topicModal->groups()->with('users.attendances')->where('type', '=', 'expert')->get();
        $absentStudentModal = Topic::find($request->input('topic_id'))->getAbsentStudents();

        return inertia('Lecturer/Session/ExpertSession', compact('topicModal', 'groupUserAttendModal', 'absentStudentModal'));
    }

    public function lecturerJigsaw(Request $request) //topic_id
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
