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
    public function index(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $topicModuleModal = $topicModal->with('modules')->first();
        $studentAttendModal = $topicModal->getAttendStudents();
        $studentAbsentModal = $topicModal->getAbsentStudents();

        return inertia('Session/Index', compact('topicModuleModal', 'studentAttendModal', 'studentAbsentModal'));

    }

    public function expertSession(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        dd($topicModal);
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
