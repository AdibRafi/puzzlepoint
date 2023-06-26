<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) //assessment_id
    {
        $assessment_id = $request->input('assessment_id');
        return inertia('Question/Create', compact('assessment_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
//        dd($request->all());
        $rightAns = $request->input('answer.right_answer');
        if ($request->input('type') === 'check') {
            $rightAns = array_keys($rightAns);
        }

//        dd($request->input('answer.right_answer'));
//        dd($request->all());
        $assessment_id = $request->input('assessment_id');
        $question = new Question();
        $question->assessment()->associate($assessment_id);
        $question->name = $request->input('name');
        $question->type = $request->input('type');
        $question->save();

        $ansInput = $request->input('answer.name');
        for ($i = 1; $i < count($request->input('answer.name')) + 1; $i++) {
            $answer = new Answer();
            $answer->question()->associate($question->id);
            $answer->name = $ansInput[$i];
            if ($question->type === 'radio') {
                if ($rightAns === $i) {
                    $answer->right_answer = 1;
                } else {
                    $answer->right_answer = 0;
                }
            } elseif ($question->type === 'check') {
                if (in_array($i, $rightAns)) {
                    $answer->right_answer = 1;
                } else {
                    $answer->right_answer = 0;
                }
            }
            $answer->save();
        }

        if (Auth::user()->wizard_status === 'onCreateAssessment'){
            Auth::user()->update([
                'wizard_status' => 'onStartSession'
            ]);
        }
        return redirect()->route('question.create', compact('assessment_id'))
            ->with('alertMessage', 'Added Question Successfully');
    }

    /**
     * Display the specified resource.
     */
    public
    function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(Question $question)
    {
        $assessment_id = $question->assessment()->pluck('id')->first();
        $questionAnswerModal = $question->load('answers');
        return inertia('Question/Edit', compact('assessment_id', 'questionAnswerModal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, Question $question) //question_name, question_type, answers { name, right_answer}
    {
        //todo: do validated using QuestionStoreRequest (need to create)
        dd($request->all());
        $question->update([
            'name' => $request->input('question_name'),
            'type' => $request->input('question_type')
        ]);

        $question->answers()->delete();
        //todo: need to add right_answer
        $ansInput = $request->input('answer.name');
        for ($i = 1; $i < count($request->input('answer.name')) + 1; $i++) {
            $answer = new Answer();
            $answer->question()->associate($question->id);
            $answer->name = $ansInput[$i];
            $answer->save();
        }

        $assessment_id = $question->assessment()->first()->id;
        $topic_id = Assessment::find($assessment_id)->topic()->first()->id;

        return redirect()->route('assessment.index', compact('topic_id'))
            ->with('alertMessage', 'Update Question Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(Question $question)
    {
        //
    }
}
