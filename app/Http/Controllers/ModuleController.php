<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

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
    public function create(Request $request)
    {
        //todo: kena encrypt
        $topicData = $request->all();
        return inertia('Lecturer/CreateTopic/Module', compact('topicData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nameInput = $request->input('name');
//        dd($nameInput[1]);

        for ($i = 1; $i < $request->no_of_modules + 1; $i++) {
            $module = new Module();
            $module->topic()->associate($request->topic_id);
            $module->name = $nameInput[$i];
            $module->save();
        }

        return redirect()->route('classroom.index');
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
