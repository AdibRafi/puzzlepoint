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
      $classroom = Classroom::create($request->validated());
      $id = $classroom->id;
      auth()->user()->classrooms()->attach($id);

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
      $topicModal = $classroom->topics()->get();
      return inertia('Classroom/Show', compact('classroom', 'topicModal'));
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
