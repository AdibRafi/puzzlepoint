<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Topic;
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
        $topicData = Topic::find($topicId);

        return inertia('Lecturer/CreateTopic/Option', compact('topicData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //topic_id / groupMethod / timeMethod / tm{}
    {
        $topicModal = Topic::find($request->topic_id);
        $groupDistribution = $request->groupMethod;
        //todo: group distribution here

        $option = new Option();
        $option->topic()->associate($topicModal->id);
        $option->group_distribution = $groupDistribution;
        if ($request->timeMethod === 'even') {
            $time = $topicModal->max_time_jigsaw / $topicModal->no_of_modules;
            for ($i = 1; $i < $topicModal->no_of_modules + 1; $i++) {
                $option->setAttribute('tm' . $i, $time);
            }
        } elseif ($request->timeMethod === 'uneven') {
            for ($i = 1; $i < $topicModal->no_of_modules + 1; $i++) {
                $option->setAttribute('tm' . $i, $request->tm[$i]);
            }
        }
        $option->save();
        $topicModal->update(['status' => 'onVerify']);

        return inertia('Lecturer/CreateTopic/Verify');
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
