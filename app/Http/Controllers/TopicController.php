<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Models\Assessment;
use App\Models\Classroom;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        //todo: kena encrypt
        $classroom_id = $request->input('classroom_id');
        return inertia('Lecturer/CreateTopic/Topic', compact('classroom_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request)
    {
        //todo:check validate
//        $request->validated();
        $startedAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->date_time)->toDateTimeString();

        //todo: fix why no_of_modules is not correct for 2 n 6
        $classroom = Classroom::find($request->classroom_id);
        $topic = new Topic();
        $topic->name = $request->name;
        $topic->no_of_modules = ($request->no_of_modules / 25) + 2;
        $topic->max_time_expert = $request->max_time_expert;
        $topic->max_time_jigsaw = $request->max_time_jigsaw;
        $topic->date_time = $startedAt;
        $topic->status = 'onModule';
        $topic->classroom()->associate($classroom);
//        dd($topic);

        $topic->save();

        $t = new Assessment();
        $t->topic()->associate($topic->id);
        $t->save();

        $topicData = [
            'id' => $topic->id,
            'no_of_modules' => $topic->no_of_modules
        ];

        return redirect()->route('module.create', $topicData);
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

    public function verify(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        return inertia('Lecturer/CreateTopic/Verify', compact('topicModal'));
    }

    public function storeVerify(Request $request) //topic_id
    {
        $classroom = Classroom::find($request->input('topic_id'));
        return redirect()->route('classroom.show', $classroom)->with('alertMessage', 'topic successfully created');
    }
}
