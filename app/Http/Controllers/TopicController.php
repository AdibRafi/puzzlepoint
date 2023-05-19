<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Module;
use App\Models\Option;
use App\Models\Topic;
use Auth;
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
   public function create(Request $request) //classroom_id
   {
      //todo: kena encrypt
      $classroom_id = $request->input('classroom_id');
      return inertia('Topic/Create', compact('classroom_id'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request) //topicData, classroomData
   {
      $topicInput = $request->input('topic');
      $modulesInput = $request->input('modules');
      $optionInput = $request->input('option');
//      dd($request->all());

      $startedAt = Carbon::createFromFormat('Y-m-d\TH:i',
         $topicInput['date_time'])->toDateTimeString();

      $classroom = Classroom::find($request->input('classroom_id'));
      $topic = new Topic();
      $topic->name = $topicInput['name'];
      $topic->no_of_modules = $topicInput['no_of_modules'];
      $topic->max_time_expert = $topicInput['max_time_expert'];
      $topic->max_time_jigsaw = $topicInput['max_time_jigsaw'];
      $topic->transition_time = $topicInput['transition_time'];
      $topic->is_expert_form = 0;
      $topic->is_jigsaw_form = 0;
      $topic->is_ready = 0;
      $topic->is_complete = 0;
      $topic->is_start = 0;
      $topic->date_time = $startedAt;
      $topic->status = 'onModule';
      $topic->classroom()->associate($classroom);

      $topic->save();

      $assessment = new Assessment();
      $assessment->is_publish = 0;
      $assessment->topic()->associate($topic->id);
      $assessment->save();

      for ($i = 0; $i < count($modulesInput); $i++) {
         $module = new Module();
         $module->topic()->associate($topic->id);
         $module->name = $modulesInput[$i]['name'];
         $module->learning_objectives = $modulesInput[$i]['learning_objectives'];

         $filePath = 'modules.' . $i . '.file_path';
         if ($request->hasFile($filePath)) {
            $file = $request->file($filePath);
            $file_path = 'modules/' . $file->getClientOriginalName();
            $file->move('modules', $file->getClientOriginalName());

            $module->file_path = $file_path;
         }
         $module->save();
      }

      $groupDistribution = $optionInput['groupMethod'];
      $timeMethod = $optionInput['timeMethod'];
      //todo: do Group distribution

      $option = new Option();
      $option->topic()->associate($topic->id);
      $option->group_distribution = $groupDistribution;
      $option->time_method = $timeMethod;
      for ($j = 1; $j < $topic->no_of_modules + 1; $j++) {
         $option->setAttribute('tm' . $j, $optionInput['tm'][$j]);
      }
      $option->save();
      $topic->update([
         'status' => 'onReady',
         'is_ready' => 1,
      ]);

      $students = $topic->getStudents();
      for ($i = 0; $i < $students->count(); $i++) {
         $attendance = new Attendance();
         $attendance->user()->associate($students->get($i));
         $attendance->topic()->associate($topic->id);
         $attendance->attend_status = 'absent';
         $attendance->date = $topic->date_time;
         $attendance->save();
      }

      if (Auth::user()->wizard_status === 'onCreateTopic') {
         Auth::user()->update([
            'wizard_status' => 'onStartSession'
         ]);
         $topic->getStudents();
      }

      $classroom = $topic->classroom()->first();
      return redirect()->route('classroom.show', $classroom)
         ->with('alertMessage', 'Topic has been created');

//      $topic_id = $topic->id;

//      return redirect()->route('module.create', compact('topic_id'));
   }

   /**
    * Display the specified resource.
    */
   public function show(Topic $topic)
   {
      $moduleModal = $topic->modules()->get();
      $studentModal = $topic->getStudents();
      $assessmentModal = $topic->assessment()->exists() ? $topic->assessment()->get() : null;
      return inertia('Topic/Show',
         compact('topic', 'moduleModal',
            'studentModal', 'assessmentModal'));
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
      $topicModal = Classroom::find($request->input('classroom_id'))->topics()->where('is_complete', '=', 1)->get();


      return inertia('Topic/Archive/Index', compact('topicModal'));
   }

   public function topicArchiveShow(Topic $topic)
   {
      $expertGroupModal = $topic->groups()
         ->where('type', '=', 'expert')
         ->with('module')
         ->with('users')
         ->get();

      $jigsawGroupModal = $topic->groups()
         ->where('type', '=', 'jigsaw')
         ->with('users')
         ->get();

      return inertia('Topic/Archive/Show', compact('topic', 'expertGroupModal', 'jigsawGroupModal'));
   }

   public function topicFirstStep(StoreTopicRequest $request)
   {
      //just check validate
   }

   public function topicSecondStep(StoreModuleRequest $request)
   {
      //just check validate
   }
}
