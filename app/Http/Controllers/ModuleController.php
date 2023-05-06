<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModuleRequest;
use App\Models\Module;
use App\Models\Topic;
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
    public function create(Request $request) //topic_id
    {
        //todo: kena encrypt
        $topicModal = Topic::find($request->input('topic_id'));
        return inertia('Module/Create', compact('topicModal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModuleRequest $request) //topic_id, modules
    {
//        dd($request->all());
        $topic_id = $request->input('topic_id');
        $modulesInput = $request->input('modules');


        for ($i = 0; $i < count($modulesInput); $i++) {
            $module = new Module();
            $module->topic()->associate($topic_id);
            $module->name = $modulesInput[$i]['name'];
            $module->learning_objectives = $modulesInput[$i]['learning_objectives'];

            $filePath = 'modules.' . $i . '.file_path';
            if ($request->hasFile($filePath)) {
                $file = $request->file($filePath);
                $file->move('modules', $file->getClientOriginalName());

                $module->file_path = $file->getClientOriginalName();
            }
            $module->save();
        }
        Topic::find($topic_id)->update(['status' => 'onOption']);

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
    public function edit(Module $module) //topic_id
    {

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

    public function editIndex(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $moduleModal = $topicModal->modules()->get();
        return inertia('Module/Edit', compact('topicModal', 'moduleModal'));
    }

    public function updateIndex(Request $request)
        //topic_id, module_id[], name{}, learning_objectives{}
    {
        $moduleId = $request->input('module_id');
        $name = $request->input('name');
        $learningObjectives = $request->input('learning_objectives');

        for ($i = 0; $i < count($moduleId); $i++) {
            $module = Module::find($moduleId[$i]);
            $module->update([
                'name' => $name[$i],
                'learning_objectives' => $learningObjectives[$i],
            ]);
        }

        $classroomModal = Topic::find($request->input('topic_id'))->classroom()->first();
        $classroomId = $classroomModal->id;

        return redirect()->route('classroom.show', $classroomId)
            ->with('alertMessage', 'Module update successfully');
    }
}
