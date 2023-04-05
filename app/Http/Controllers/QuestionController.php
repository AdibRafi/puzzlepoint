<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Question;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {

        $assessment_id = $request->assessment_id;
        //todo: validate request
        $question = new Question();
        $question->assessment()->associate($assessment_id);
        $question->name = $request->question_name;
        $question->type = $request->question_type;
        $question->save();

        $ansInput = $request->input('answer.name');
        for ($i = 1; $i < count($request->input('answer.name')) + 1; $i++) {
            $answer = new Answer();
            $answer->question()->associate($question->id);
            $answer->name = $ansInput[$i];
            $answer->save();
        }
        return redirect()->route('question.create', compact('assessment_id'))
            ->with('alertMessage', 'Added Question Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $assessment_id = $question->assessment()->pluck('id')->first();
        $questionAnswerModal = $question->with('answers')->first();
        return inertia('Question/Edit',compact('assessment_id','questionAnswerModal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
