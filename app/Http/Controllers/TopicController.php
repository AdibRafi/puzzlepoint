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
use Illuminate\Database\Query\Builder;
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
        $classroomModal = Classroom::find($request->input('classroom_id'))
            ->loadCount([
                'users',
                'users as users_count' => function ($query) {
                    $query->where('type', '=', 'student');
                }
            ]);

        return inertia('Topic/Create', compact('classroomModal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //topicData, classroomData
    {
//        dd($request->all());
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
        $topic->max_session = $topicInput['max_session'];
        $topic->max_buffer = $topicInput['max_buffer'];
        $topic->max_time_expert = $topicInput['max_time_expert'];
        $topic->max_time_jigsaw = $topicInput['max_time_jigsaw'];
        $topic->transition_time = $topicInput['transition_time'];
        $topic->is_buffer_add = 0;
        $topic->is_expert_form = 0;
        $topic->is_jigsaw_form = 0;
        $topic->is_new = 1;
        $topic->is_complete = 0;
        $topic->is_start = 0;
        $topic->date_time = $startedAt;
        $topic->status = 'onModule';
        $topic->classroom()->associate($classroom);

        $topic->save();

        $assessment = new Assessment();
        $assessment->is_publish = 0;
        $assessment->is_ready_publish = 0;
        $assessment->topic()->associate($topic->id);
        $assessment->is_complete = 0;
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

        $groupDistribution = $optionInput['group_distribution'];
        $timeMethod = $optionInput['time_method'];

        $option = new Option();
        $option->topic()->associate($topic->id);
        $option->group_distribution = $groupDistribution;
        $option->time_method = $timeMethod;
        for ($j = 0; $j < count($optionInput['tm']); $j++) {
            $option->setAttribute(('tm' . ($j + 1)), $optionInput['tm'][$j]);
        }
        $option->save();
        $topic->update([
            'status' => 'onReady',
        ]);

        $attendanceStatus = 'absent';
        if (Auth::user()->wizard_status === 'onCreateTopic') {
            Auth::user()->update([
                'wizard_status' => 'onCreateAssessment'
            ]);
            $attendanceStatus = 'present';
        }
        $students = $topic->getStudents();
        for ($i = 0; $i < $students->count(); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($students->get($i));
            $attendance->topic()->associate($topic->id);
            $attendance->attend_status = $attendanceStatus;
            $attendance->date = $topic->date_time;
            $attendance->save();
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
        $topic->update([
            'is_new' => 0,
        ]);

        $moduleModal = $topic->modules()->get();
        $studentModal = $topic->getStudents();
        $classroomModal = $topic->classroom()->first();

        $assessmentStatus = 1;
        $sessionStatus = 0;
        if (Auth::user()->type === 'student') {
            if ($topic->assessment()->first()->is_ready_publish === 1) {
                $pivot = Assessment::find($topic->assessment()->first()->id)
                    ->users()
                    ->where('id', '=', Auth::id())
                    ->withPivot('is_finish')
                    ->first();
                $assessmentModal = Assessment::find($topic->assessment()->first()->id);
                $assessmentModal->is_assessment_finish = $pivot->pivot->is_finish;
                if ($assessmentModal->is_assessment_finish
                    || $assessmentModal->is_publish === 0) {
                    $assessmentStatus = 0;
                }
            } else {
                $assessmentModal = Assessment::find($topic->assessment()->first()->id);
                $assessmentModal->is_assessment_finish = null;
                $assessmentStatus = 0;
            }
        } else {
            $assessmentModal = Assessment::find($topic->assessment()->first()->id);
        }

        if ($assessmentModal->is_ready_publish === 1
            && $topic->is_complete === 0 &&
            Carbon::now() >= Carbon::parse($topic->date_time)) {
            $sessionStatus = 1;
        }

        if (Auth::user()->wizard_status === 'onStartSession') {
            $assessmentStatus = 0;
        }


        return inertia('Topic/Show',
            compact('topic', 'moduleModal',
                'studentModal', 'assessmentModal', 'classroomModal',
                'assessmentStatus', 'sessionStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        $option = $topic->option()->first();
        $modules = $topic->modules()->get();
        $classroom = $topic->classroom()->withCount([
            'users',
            'users as users_count' => function ($query) {
                $query->where('type', '=', 'student');
            }
        ])->first();

//        $classroomModal = Classroom::find($request->input('classroom_id'))
//            ->loadCount([
//                'users',
//                'users as users_count' => function ($query) {
//                    $query->where('type', '=', 'student');
//                }
//            ]);


        return inertia('Topic/Edit',
            compact('topic', 'option', 'modules', 'classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
//
    }

    public function updateTopic(Request $request)
    {
        $topic = Topic::find($request->input('topic')['id']);
        $topic->update($request->input('topic'));
        $topic->modules()->delete();
        $modulesInput = $request->input('modules');

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

        $topic->option()->delete();
        $optionInput = $request->input('option');

        $option = new Option();
        $option->topic()->associate($topic->id);
        $option->group_distribution = $optionInput['group_distribution'];
        $option->time_method = $optionInput['time_method'];
        for ($j = 0; $j < count($optionInput['tm']); $j++) {
            $option->setAttribute(('tm' . ($j + 1)), $optionInput['tm'][$j]);
        }
        $option->save();

        return redirect()->route('topic.show', $topic)
            ->with('alertMessage', 'Topic Successfully Updated');
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

        $classroomModal = Classroom::find($request->input('classroom_id'));
        $topicModuleModal = $classroomModal
            ->topics()
            ->where('is_complete', '=', 1)
//            ->with('modules')
            ->whereHas('assessment', function ($q) {
                return $q->where('is_complete', '=', 1);
            })
//            ->with('assessment')

//            ->where('assessment.is_complete', '=', '1')
//            ->with(['assessment' => function ($q) {
//                $q->where('is_complete', '=', 1);
//            }])
            ->get();

//        dd($topicModuleModal);

        return inertia('Topic/Archive/Index',
            compact('topicModuleModal', 'classroomModal'));
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
            ->with('users', function ($q) {
                $q->withPivot('module_id')
                    ->withPivot('module_name');
            })
            ->get();

        $moduleModal = $topic->modules()->get();
        $studentModal = $topic->getStudents();

        $assessmentModal = $topic->assessment()->first();
        $questionAnswerModal = $assessmentModal->questions()->with('answers')->get();

        if (Auth::user()->type === 'lecturer') {
            $studentAssessmentModal = Assessment::find($assessmentModal->id)->users()
                ->withPivot('marks')
                ->get();
        } else
            $studentAssessmentModal = Assessment::find($assessmentModal->id)->users()
                ->where('id', '=', Auth::id())
                ->withPivot('marks')
                ->withPivot('is_finish')
                ->first();

        $classroomModal = $topic->classroom()->first();

        return inertia('Topic/Archive/Show',
            compact('topic', 'expertGroupModal',
                'jigsawGroupModal', 'moduleModal', 'studentModal'
                , 'assessmentModal', 'questionAnswerModal', 'studentAssessmentModal'
                , 'classroomModal'));
    }

    public function topicFirstStep(StoreTopicRequest $request)
    {
        //just check validate
    }

    public function topicSecondStep(StoreModuleRequest $request)
    {
        //just check validate
    }

    public function topicWizardStep(Request $request) //steps
    {
        $steps = $request->input('steps');
        if ($steps === 1) {
            $request->validate([
                'name' => 'required',
                'date_time' => 'required'
            ], [
                'name.required' => 'Please fill in Topic Name',
                'date_time.required' => 'Please Specify the date'
            ]);
        } elseif ($steps === 2) {
            $request->validate([
                'no_of_modules' => 'required'
            ], [
                'no_of_modules.required' => 'Please select the desired Number of Modules'
            ]);
        } elseif ($steps === 3) {
            $request->validate([
                'modules.*.name' => 'required'
            ], [
                'modules.*.name.required' => 'Please fill in all modules name'
            ]);
        } elseif ($steps === 4) {
            $request->validate([
                'option.groupMethod' => 'required',
                'option.timeMethod' => 'required',
            ], [
                'option.groupMethod.required' => 'Please specify the group method',
                'option.timeMethod.required' => 'Please specify the time method',
            ]);
        } elseif ($steps === 5) {
            $request->validate([
                'max_session' => 'required',
                'max_buffer' => 'required'
            ], [
                'max_session.required' => 'Please specify the overall time session',
                'max_buffer.required' => 'Please specify the buffer time',
            ]);
        } elseif ($steps === 6) {
            $request->validate([
                'date_time' => 'required'
            ], [
                'date_time.required' => 'Please specify the date to start the topic'
            ]);
        }
    }

    public function duplicateTopic(Request $request)
    {
        $topicOld = Topic::find($request->input('topic_id'));
        $topicNew = $topicOld->replicate()->fill([
            'is_buffer_add' => 0,
            'is_expert_form' => 0,
            'is_jigsaw_form' => 0,
            'is_new' => 1,
            'is_start' => 0,
            'is_complete' => 0,
        ]);
        $topicNew->save();

        $optionNew = $topicOld->option()->first()->replicate();
        $optionNew->topic()->associate($topicNew);
        $optionNew->save();


        $assessmentNew = new Assessment();
        $assessmentNew->topic()->associate($topicNew);
        $assessmentNew->is_ready_publish = 0;
        $assessmentNew->is_publish = 0;
        $assessmentNew->is_complete = 0;
        $assessmentNew->save();

        $modulesOld = $topicOld->modules()->get();

        foreach ($modulesOld as $value) {
            $moduleNew = $value->replicate();
            $moduleNew->topic()->associate($topicNew);
            $moduleNew->save();
        }


        $students = $topicNew->getStudents();
        for ($i = 0; $i < $students->count(); $i++) {
            $attendance = new Attendance();
            $attendance->user()->associate($students->get($i));
            $attendance->topic()->associate($topicNew);
            $attendance->attend_status = 'absent';
            $attendance->date = $topicNew->date_time;
            $attendance->save();
        }


        $classroom = $topicOld->classroom()->first();

        return redirect()->route('classroom.show', $classroom)
            ->with('alertMessage', 'Duplicate Topic Successful');
    }

    public function topicValidateStep(Request $request)// steps, data
    {
//        dd('idk');
        $steps = $request->input('steps');
        if ($steps === 1) {

            $request->validate([
                'name' => 'required',
                'date_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:' . date('Y-m-d\TH:i'),
//                after_or_equal:' . date(DATE_ATOM)
            ], [
                'name.required' => 'Please fill in the name of the Topic',
                'date_time.required' => 'Please Specify the date to start the topic',
                'date_time.after_or_equal' => 'Please Choose After Today'
            ]);
        } elseif ($steps === 2) {
//            dd($request->all());
            $request->validate([
                'modules.*.name' => 'required'
            ], [
                'modules.*.name.required' => 'Please fill in the name of the modules'
            ]);

            for ($i = 0; $i < count($request->input('modules')); $i++) {
                $filePath = 'modules.' . $i . '.file_path';
                if ($request->hasFile($filePath)) {
                    $file = $request->file($filePath);
                    $file->move('modules', $file->getClientOriginalName());

                }
            }
        } elseif ($steps === 3) {
            $request->validate([
                'group_distribution' => 'required',
                'time_method' => 'required',
            ], [
                'group_distribution.required' => "Please choose the group method",
                'time_method.required' => 'Please choose the time method',
            ]);
        } elseif ($steps === 4) {
            $request->validate([
                'max_session' => 'required',
                'max_buffer' => 'required'
            ], [
                'max_session.required' => 'Please specify the overall time session',
                'max_buffer.required' => 'Please specify the buffer time',
            ]);
        }
    }
}
