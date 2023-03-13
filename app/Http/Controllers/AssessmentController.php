<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Topic;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $topicData = $request->input('topic_id');
        $questionData = Assessment::find($request->input('topic_id'))->questions()->get();
        $assessmentData = Topic::find($topicData)->assessment()->first();
//        dd($assessmentData);

        return inertia('Lecturer/Assessment/Index', compact('topicData', 'questionData', 'assessmentData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
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
