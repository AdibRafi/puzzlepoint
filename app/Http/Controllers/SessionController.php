<?php

namespace App\Http\Controllers;

use App\Events\AttendanceExpert;
use App\Models\Group;
use App\Models\Topic;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SessionController extends Controller
{
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
        $groupUserModal = $topicModal->groups()->with('users')->where('type', '=', 'expert')->get();

        return inertia('Lecturer/Session/ExpertSession', compact('topicModal', 'groupUserModal'));
    }

    public function lecturerJigsaw(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $groupUserModal = $topicModal->groups()->with('users')->where('type', '=', 'jigsaw')->get();

        return inertia('Lecturer/Session/JigsawSession', compact('topicModal', 'groupUserModal'));
    }

    public function expertPusher()
    {
        broadcast(new AttendanceExpert('ok dpt'));
    }
}
