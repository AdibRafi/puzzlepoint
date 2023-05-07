<?php

namespace App\Http\Controllers;

use App\Events\MoveExpertSession;
use App\Events\MoveJigsawSession;
use App\Events\StudentAttendance;
use App\Events\TimeSession;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function studentAttendance($topic_id)
    {
        $topicModal = Topic::find($topic_id);

        $a = $topicModal->attendances()->get();
        $b = Auth::user()->attendances()->get();
        $c = collect();
        $c = $c->merge($a)->merge($b)->duplicates()->values();

        foreach ($c as $value) {
            $value->update([
                'attend_status' => 'present'
            ]);
        }

        broadcast(new StudentAttendance(Auth::user()->name . ' is present'));
    }

    public function timeSession($minuteCounter, $secondCounter,
                                $transitionMinuteCounter, $transitionSecondCounter)
    {
        TimeSession::dispatch($minuteCounter, $secondCounter, $transitionMinuteCounter, $transitionMinuteCounter);
    }
}
