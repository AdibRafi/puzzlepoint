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
    public function index(Request $request) //topic_id OR assessment_id
    {
        $assessmentModal = Assessment::find($request->input('assessment_id'));
        $topicModal = Topic::find($request->input('topic_id'));
        if ($assessmentModal !== null) {
            $topicModal = $assessmentModal->topic()->first();
            $questionAnswerModal = $assessmentModal->questions()->with('answers')->get();
            return inertia('Assessment/Index',
                compact('topicModal', 'assessmentModal', 'questionAnswerModal'));
        } else if ($topicModal->assessment()->exists()) {
            $assessmentModal = $topicModal->assessment()->first();
            $questionAnswerModal = Assessment::find($assessmentModal->id)->questions()->with('answers')->get();
            return inertia('Assessment/Index',
                compact('topicModal', 'questionAnswerModal', 'assessmentModal'));
        } else {
            $assessmentModal = new Assessment();
            $assessmentModal->topic()->associate($topicModal->id);
            $assessmentModal->is_publish = 0;
            $assessmentModal->save();
            return inertia('Assessment/Index',
                compact('assessmentModal', 'topicModal'));
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
        return redirect()->route('student.assessment.index', ['topic_id' => $topicId])
            ->with('alertMessage', 'You got ' . $score . ' scores');
    }

    public function publishAssessment(Request $request) //assessment_id, time
    {
        $assessmentModal = Assessment::find($request->input('assessment_id'));

        $topicModal = $assessmentModal->topic()->first();

        $topic = Topic::find($topicModal->id)->getStudents()->pluck('id');

        $assessmentModal->users()->attach($topicModal->getStudents()->pluck('id'));

        $assessmentModal->update([
            'is_publish' => 1,
            'time' => $request->input('time')
        ]);

        if (Auth::user()->wizard_status === 'onPublishAssessment') {
            Auth::user()->update([
                'wizard_status' => 'onEndAssessment'
            ]);
        }

        return redirect()->route('topic.show', $topicModal)
            ->with('alertMessage', 'Assessment Publish Successfully');
    }

    public function endAssessmentSession(Request $request) //assessment_id
    {
        $assessmentModal = Assessment::find($request->input('assessment_id'));
        $topicModal = $assessmentModal->topic()->first();
        $classroomModal = $topicModal->classroom()->first();

        $assessmentModal->update([
            'is_complete' => 1,
        ]);

        if (Auth::user()->wizard_status === 'onEndAssessment') {
            Auth::user()->update([
                'wizard_status' => 'onShowArchive'
            ]);
        }

        return redirect()->route('classroom.show',$classroomModal)
            ->with('alertMessage','End Assessment Successfully');
    }
}
