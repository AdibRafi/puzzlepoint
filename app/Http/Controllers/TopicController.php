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
        return inertia('Topic/Create', compact('classroom_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request) //topicData, classroomData
    {
        $startedAt = Carbon::createFromFormat('Y-m-d\TH:i', $request->date_time)->toDateTimeString();

        $classroom = Classroom::find($request->classroom_id);
        $topic = new Topic();
        $topic->name = $request->name;
        $topic->no_of_modules = $request->no_of_modules;
        $topic->max_time_expert = $request->max_time_expert;
        $topic->max_time_jigsaw = $request->max_time_jigsaw;
        $topic->transition_time = $request->transition_time;
        $topic->is_expert_form = 0;
        $topic->is_jigsaw_form = 0;
        $topic->is_ready = 0;
        $topic->is_complete = 0;
        $topic->date_time = $startedAt;
        $topic->status = 'onModule';
        $topic->classroom()->associate($classroom);
//        dd($topic);

        $topic->save();

        $t = new Assessment();
        $t->isPublish = 0;
        $t->topic()->associate($topic->id);
        $t->save();

        $topic_id = $topic->id;

        return redirect()->route('module.create', compact('topic_id'));
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
        $classroom = $topic->classroom()->first();
        if ($topic->assessment()->exists()) {
            $topic->assessment()->delete();
        }
        $topic->attendances()->delete();
        $topic->modules()->delete();
        $topic->delete();

        return redirect()->route('classroom.show', $classroom)
            ->with('alertMessage', 'Topic Delete Successfully');

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

    public function topicArchiveIndex(Request $request) //classroom_id
    {
        dd($request->all());
    }
    public function topicArchiveShow(Topic $topic)
    {

    }
}
