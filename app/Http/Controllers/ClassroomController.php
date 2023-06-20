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
    public function store(Request $request)
    {
        if (Auth::user()->type === 'student') {
            $request->validate([
                'join_code' => 'required'
            ], [
                'join_code.required' => 'Please Enter the join code'
            ]);

            $classroom = Classroom::where('join_code', '=', $request->input('join_code'))
                ->first();
            if ($classroom === null) {
                return back()->with('warningMessage', 'The code you enter is not in database');
            } else {
                if ($classroom->users()->where('id', '=', Auth::id())->exists()) {
                    return back()->with('warningMessage', 'You already join this class');
                } else {
                    $classroom->users()->attach(Auth::user());
                    return redirect()->route('classroom.index')
                        ->with('alertMessage', 'Class successfully added');
                }
            }
        } else {
            $request->validate([
                'name' => 'required',
                'subject_code' => 'required',
            ], [
                'name.required' => 'Please Fill in Name',
                'subject_code.required' => 'Please Fill in Subject Code'
            ]);
            $classroom = new Classroom();
            $classroom->name = $request->input('name');
            $classroom->subject_code = $request->input('subject_code');
            $classroom->join_code = Str::random(6);
            $classroom->is_new = 1;
            $classroom->save();

            auth()->user()->classrooms()->attach($classroom->id);

            if (Auth::user()->wizard_status === 'onCreateClassroom') {
                Auth::user()->update([
                    'wizard_status' => 'onAddStudent'
                ]);
                User::where('type', '=', 'student')->each(function ($user) use ($classroom) {
                    $user->classrooms()->attach($classroom->id);
                });
            }
        }

        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $classroom->update([
            'is_new' => 0,
        ]);

        $topicModal = $classroom->topics()
            ->where('is_complete', '=', 0)
            ->orWhere('is_complete', '=', 1)
            ->whereHas('assessment', function ($query) {
                $query->where('is_complete', '=', 0);
            })
            ->get();

//        dd($topicModal);
        $topicArchiveModal = $classroom->topics()
            ->where('is_complete', '=', 1)
            ->whereHas('assessment', function ($query) {
                $query->where('is_complete', '=', 1);
            })->get();

        $studentModal = $classroom->users()
            ->where('type', '=', 'student')
            ->get();
        return inertia('Classroom/Show',
            compact('classroom', 'topicModal',
                'topicArchiveModal', 'studentModal'));
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

    public function addStudentCreate(Request $request) //classroom_id
    {
//        dd($request->all());
        $classroom_id = $request->input('classroom_id');
        return inertia('Classroom/AddStudent', compact('classroom_id'));
    }

    public function addStudentStore(Request $request) //name, email, numGenerateStudents, classroom_id
    {
        if (Auth::user()->wizard_status === 'onAddStudent') {
            $request->validate([
                'numGenerateStudents' => 'required'
            ], [
                'numGenerateStudents.required' => 'Please specify the number you want the dummy students',
            ]);
            $user = User::factory($request->input('numGenerateStudents'))->create([
                'type' => 'student'
            ]);
            Classroom::find($request->input('classroom_id'))->users()->attach($user);

            Auth::user()->update([
                'wizard_status' => 'onCreateTopic'
            ]);
        } else {
            $request->validate([
                'name' => 'required'
            ], [
                'name.required' => 'Please Enter Student Name'
            ]);

            User::create([
                'name' => $request->input('name'),
                'type' => 'student',
            ]);
        }

        return redirect()->route('classroom.show', $request->input('classroom_id'))->with('alertMessage', 'Add Student Successfully');
    }
}
