<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Topic;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function studentExpert(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $user = auth()->user();
        $groupModal = User::find(auth()->id())->groups()->get();

        dd($groupModal);
        return inertia('Student/Session/ExpertSession',compact('topicModal','groupModal'));

    }
}
