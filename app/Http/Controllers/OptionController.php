<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Group;
use App\Models\Option;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class OptionController extends Controller
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
    public function create(Request $request) //topic_id
    {
        $topicId = $request->input('topic_id');
        $topicModal = Topic::find($topicId);

        return inertia('Lecturer/CreateTopic/Option', compact('topicModal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //topic_id / groupMethod / timeMethod / tm{}
    {
        dd($request);
        $topicModal = Topic::find($request->topic_id);
        $groupDistribution = $request->groupMethod;
        //todo: create group modal first then distribute users
        $groupModal = $topicModal->modules();
        //todo: group distribution here
//        $topicModal->modules()->each(function ($modules) use ($groups) {
//            $modules->groups()->attach(1);
//        });
//        dd($topicModal->getUsers());
        for ($i = 1; $i < count($topicModal->getUsers()) + 1; $i++) {
            $group = new Group();
        }

        //distribution

        $option = new Option();
        $option->topic()->associate($topicModal->id);
        $option->group_distribution = $groupDistribution;
        if ($request->timeMethod === 'even') {
            $time = $topicModal->max_time_jigsaw / $topicModal->no_of_modules;
            for ($i = 1; $i < $topicModal->no_of_modules + 1; $i++) {
                $option->setAttribute('tm' . $i, $time);
            }
            //todo: calculate and store it inside db
        } elseif ($request->timeMethod === 'uneven') {
//            dd(count($request->input('tm')));
            for ($i = 1; $i < $topicModal->no_of_modules + 1; $i++) {
                $option->setAttribute('tm' . $i, $request->tm[$i]);
            }
        }
        $option->save();
        $topicModal->update(['status' => 'onVerify']);

        return inertia('Lecturer/CreateTopic/Verify',compact('topicModal'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Option $option)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Option $option)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Option $option)
    {
        //
    }
}
