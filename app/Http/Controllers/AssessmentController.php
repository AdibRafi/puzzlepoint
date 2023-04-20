<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\Topic;
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
            $assessmentModal->save();
            return inertia('Assessment/Index', compact('assessmentModal','topicModal'));
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
        dd($assessment);
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
        //
    }
}
