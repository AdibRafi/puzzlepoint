<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Group;
use App\Models\Module;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $classroomData = Classroom::all();
//        $classroomData = Classroom::find(2)->users->load('classrooms')->all();


//        $classroomData = User::find(1)->classrooms()->first();

        //todo: because user is Auth, then find way to m2m
//        $classroomData = Group::find(1)->modules()->get();
        $classroomData = auth()->user()->classrooms()->get();
//        $user = auth()->user();
//        $user->classrooms;
//        dd($data);
//        $classroomData = auth()->user()->classrooms;
//        $classroomData = User::with('classrooms')->find(Auth::id())->get();


//        $classroomData = User::find(Auth::id())->classrooms()->get();
//        print_r(Auth::id());


//        $classroomData = User::find(1)->classrooms()->get();
//        $classroomData = User::find(1)->classrooms->all();
//        print_r($classroomData);
//        $classroomData = Classroom::where('id');
//        $classroomData = User::find(1)->classrooms()->get();
//        $classroomData = User::insertGetId([
//           'user_id' => $user
//        ]);
//        print_r($classroomData);


//        $classroomData = Classroom::with('user')->get();
//        $classroomData = User::find(1)->classrooms()->get();
//        $classroomData = User::find(1)->with('classrooms')->get();

        return inertia('Dashboard', compact('classroomData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        //
    }
}
