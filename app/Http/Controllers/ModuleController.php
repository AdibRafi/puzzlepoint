<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Topic;
use Illuminate\Http\Request;
use function Sodium\add;

class ModuleController extends Controller
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
    //todo: request can only just use topic_id -> use topicModal
    public function create(Request $request) //topic_id
    {
        //todo: kena encrypt
        $topicModal = Topic::find($request->input('topic_id'));
        return inertia('Lecturer/CreateTopic/Module', compact( 'topicModal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nameInput = $request->input('name');
        $topic_id = $request->topic_id;
        $no_of_modules = $request->no_of_modules;
        $learningObjectivesInput = $request->input('learning_objectives');

//        dd($learningObjectivesInput);

        for ($i = 1; $i < $no_of_modules + 1; $i++) {
            $module = new Module();
            $module->topic()->associate($topic_id);
            $module->name = $nameInput[$i];
            $module->learning_objectives = $learningObjectivesInput[$i];
            $module->save();
        }
        Topic::find($request->topic_id)->update(['status' => 'onOption']);


        return redirect()->route('option.create', compact('topic_id'));
//        dd($module[1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        //
    }
}
