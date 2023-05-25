<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassroomRequest;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Module;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classroomData = auth()->user()->classrooms()->get();

        return inertia('Classroom/Index', compact('classroomData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Classroom/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        $classroom = new Classroom();
        $classroom->name = $request->input('name');
        $classroom->subject_code = $request->input('subject_code');
        $classroom->join_code = Str::random(6);
        $classroom->save();

        auth()->user()->classrooms()->attach($classroom->id);

        if (Auth::user()->wizard_status === 'onCreateClassroom') {
            Auth::user()->update([
                'wizard_status' => 'onCreateTopic'
            ]);
            User::where('type', '=', 'student')->each(function ($user) use ($classroom) {
                $user->classrooms()->attach($classroom->id);
            });
        }

        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $topicModal = $classroom->topics()->where('is_complete', '=', 0)->get();
        $topicArchiveModal = $classroom->topics()->where('is_complete', '=', 1)->get();
        return inertia('Classroom/Show',
            compact('classroom', 'topicModal', 'topicArchiveModal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        return inertia('Classroom/Edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        //todo: detach topic n its relationship
        $classroom->users()->detach();
        $classroom->delete();

        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom deleted successfully');
    }
}
