<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Models\Classroom;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TopicController extends Controller
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
    public function create(Request $request)
    {
        $classroom_id = $request->input('classroom_id');
        return inertia('Lecturer/CreateTopic/Topic',compact('classroom_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request)
    {
        //todo:check validate
//        $request->validated();
        $startedAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->date_time)->toDateTimeString();

        $classroom = Classroom::find($request->classroom_id);
        $topic = new Topic();
        $topic->name = $request->name;
        $topic->no_of_modules = ($request->no_of_modules / 25) + 2;
        $topic->max_time_expert = $request->max_time_expert;
        $topic->max_time_jigsaw = $request->max_time_jigsaw;
        $topic->date_time = $startedAt;
        $topic->status = 'ongoing';
        $topic->classroom()->associate($classroom);

        $topic->save();


        //todo: find a way to pass in data about topic_id n no_of_modules
        return inertia('Lecturer/CreateTopic/Module',compact($topic));
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        //
    }
}
