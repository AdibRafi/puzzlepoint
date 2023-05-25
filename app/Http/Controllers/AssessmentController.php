<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\Topic;
use Auth;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        if ($topicModal->assessment()->exists()) {
            $assessmentModal = $topicModal->assessment()->first();
            $questionAnswerModal = Assessment::find($assessmentModal->id)->questions()->with('answers')->get();
            return inertia('Assessment/Index', compact('topicModal', 'questionAnswerModal', 'assessmentModal'));
        } else {
            $assessmentModal = new Assessment();
            $assessmentModal->topic()->associate($topicModal->id);
            $assessmentModal->isPublish = 0;
            $assessmentModal->save();
            return inertia('Assessment/Index', compact('assessmentModal', 'topicModal'));
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) //topic_id
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Assessment $assessment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assessment $assessment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assessment $assessment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assessment $assessment)
    {
        $topicModal = $assessment->topic()->first();
        $classroom = Topic::find($topicModal->id)->classroom()->first();

        $assessment->delete();

        return redirect()->route('classroom.show', $classroom)
            ->with('alertMessage', 'Assessment Delete Successfully');
    }

    public function studentAssessmentIndex(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        if ($topicModal->assessment()->exists()) {
            $assessmentModal = $topicModal->assessment()->first();
            return inertia('Assessment/Student/Index', compact('topicModal', 'assessmentModal'));
        } else {
            $classroom = $topicModal->classroom()->first();
            return redirect()->route('classroom.show', $classroom->id)
                ->with('warningMessage', "There is no assessment made");
        }
    }

    public function studentSessionIndex(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $assessmentModal = $topicModal->assessment()->first();
        $questionAnswerModal = Assessment::find($assessmentModal->id)->questions()->with('answers')->get();

        return inertia('Assessment/Student/Session', compact('topicModal', 'assessmentModal', 'questionAnswerModal'));
    }

    public function studentCheckAnswer(Request $request) //topic_id, name, type, options, answer
    {
        $ansInput = collect($request->all());
//        dd(Question::find(4)->answers()->where('right_answer', '=', '1')->pluck('right_answer')->first());
        $score = 0;
        for ($i = 0; $i < count($request->all()); $i++) {
            $questionModal = Question::find($ansInput->pluck('id')[$i]);
            if ($ansInput->pluck('type')[$i] === 'radio') {
                if ($ansInput->pluck('answer')[$i] === $questionModal->answers()->where('right_answer', '=', '1')->pluck('name')->first()) {
                    $score++;
                }
            } elseif ($ansInput->pluck('type')[$i] === 'check') {
                $a = $ansInput->pluck('answers')[$i];
                $b = $questionModal->answers()->where('right_answer', '=', '1')->pluck('name')->toArray();
                if (count($a) === count($b)) {
                    if ($a === $b) {
                        $score++;
                    }
                }

                if ($ansInput->pluck('answers')[$i] === $questionModal->answers()->where('right_answer', '=', '1')->pluck('name')->collect()) {

                    $score++;
                }
            }
        }

        $assessmentId = Question::find($ansInput->pluck('id')[0])->assessment()->first()->pluck('id');
        $assessmentModal = Assessment::find($assessmentId)->first();

        $assessmentModal->users()->updateExistingPivot(Auth::id(), ['marks' => $score]);
//        dd(Auth::user());
//        Assessment::find(1)->users()->attach(2);
        $topicId = $assessmentModal->topic()->first()->id;
//        dd($topicId);
        return redirect()->route('student.assessment.index',['topic_id' => $topicId])
            ->with('alertMessage', 'You got ' . $score . ' scores');
    }

    public function publishAssessment(Request $request) //topic_id, time
    {
        dd($request->all());
        $topic_id = $request->input('topic_id');
        Topic::find($topic_id)->assessment()->update([
            'isPublish' => 1,
            'time' => $request->input('time'),
        ]);

        return redirect()->route('assessment.index', compact('topic_id'))
            ->with('alertMessage', 'Question Publish Successfully');
    }
}
