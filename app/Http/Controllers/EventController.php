<?php

namespace App\Http\Controllers;

use App\Events\MoveExpertSession;
use App\Events\MoveJigsawSession;
use App\Events\StudentAttendance;
use App\Events\TimeSession;
use App\Events\UpdateExpertSession;
use App\Events\UpdateJigsawSession;
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


    public function updateTime(Request $request) //time related, sessionType
    {
//        dd($request->all());
        $minuteCounter = $request->input('minuteCounter');
        $secondCounter = $request->input('secondCounter');
        $transitionMinuteCounter = $request->input('transitionMinuteCounter');
        $transitionSecondCounter = $request->input('transitionSecondCounter');

        if ($request->input('sessionType') === 'expert') {
            UpdateExpertSession::dispatch($minuteCounter, $secondCounter,
                $transitionMinuteCounter, $transitionSecondCounter);
        } else {
            $moduleMinuteCounter = $request->input('moduleMinuteCounter');
            $moduleSecondCounter = $request->input('moduleSecondCounter');
            $moduleNumber = $request->input('moduleNumber');

            UpdateJigsawSession::dispatch($minuteCounter, $secondCounter,
                $transitionMinuteCounter, $transitionSecondCounter,
                $moduleMinuteCounter, $moduleSecondCounter, $moduleNumber);

        }
    }

    public function moveExpert(): void
    {
        MoveExpertSession::dispatch();
    }

    public function moveJigsaw(): void
    {
        MoveJigsawSession::dispatch();
    }
}
