<?php

namespace App\Http\Controllers;

use App\Events\EndSession;
use App\Events\MoveExpertSession;
use App\Events\MoveJigsawSession;
use App\Events\StudentAttendance;
use App\Events\TimeSession;
use App\Events\UpdateExpertSession;
use App\Events\UpdateJigsawSession;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Module;
use App\Models\Topic;
use App\Models\User;
use Auth;
use Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use function Sodium\add;

class SessionController extends Controller
{
    private function initiateTopicModuleStudentModal($topic_id)
    {
        $topicModuleModal = Topic::find($topic_id)->load('modules');
        $studentAttendModal = $topicModuleModal->getAttendStudents();
        $studentAbsentModal = $topicModuleModal->getAbsentStudents();

        return [$topicModuleModal, $studentAttendModal, $studentAbsentModal];
    }

    public function lecturerIndexSession(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        $timeFromDb = Carbon::parse($topicModuleModal->date_time);

        $timeAddBuffer = $timeFromDb->addMinutes($topicModuleModal->max_buffer)->format('H:i');

        $topicModuleModal->update([
            'is_start' => 1,
        ]);

        return inertia('Session/Index', compact('topicModuleModal',
            'studentAttendModal', 'studentAbsentModal', 'timeAddBuffer'));

    }

    public function lecturerExpertSession(Request $request) //topic_id //Time
    {

        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        if ($topicModuleModal->is_buffer_add === 0) {
            $timeFromDb = Carbon::parse($topicModuleModal->date_time);
            $timeNow = Carbon::now();

            $diff = $timeNow->diff($timeFromDb);

            $remainderTime = ($topicModuleModal->max_buffer) - $diff->i;
//          todo: divide with student present on jigsaw session
            if ($remainderTime > 0) {
                $splitTime = floor($remainderTime / 2);
                $topicModuleModal->update([
                    'max_session' => ($topicModuleModal->max_session + $remainderTime),
                    'max_time_expert' => ($topicModuleModal->max_time_expert + $splitTime),
                    'max_time_jigsaw' => ($topicModuleModal->max_time_jigsaw + $splitTime),
                ]);
            }
            $topicModuleModal->update([
                'is_buffer_add' => 1,
            ]);
        }


        if ($topicModuleModal->is_expert_form === 0) {

            //todo: PRIORITY, distribution thing
            if ($topicModuleModal->option()->first()->group_distribution === 'random') {
                $doneShuffleStudent = $studentAttendModal->shuffle();
            } else {
                $groupUsers = $studentAttendModal->groupBy('gender');
                $shuffledUsers = $groupUsers->map(function ($group) {
                    return $group->shuffle();
                });

                $doneShuffleStudent = collect();

                $maleUsers = $shuffledUsers['male'];
                $femaleUsers = $shuffledUsers['female'];

                $maxCount = max($maleUsers->count(), $femaleUsers->count());

                for ($l = 0; $l < $maxCount; $l++) {
                    if ($maleUsers->count() > $l) {
                        $doneShuffleStudent->push($maleUsers[$l]);
                    }
                    if ($femaleUsers->count() > $l) {
                        $doneShuffleStudent->push($femaleUsers[$l]);
                    }
                }
            }
            $numOfModules = $topicModuleModal->no_of_modules;
            $modulesId = $topicModuleModal->modules->pluck('id');

            (int)$totalGroup = floor(count($doneShuffleStudent) / $topicModuleModal->no_of_modules);

            if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
                $numOfModules *= 2;
//                $totalGroup /= 2;
                $modulesId = $modulesId->merge($modulesId);
            }

            $splitUser = $doneShuffleStudent->split($numOfModules);


//            dd(count($splitUser[0]) >= 7);
            if (count($splitUser[0]) > 7) {
                while (count($splitUser[0]) > 7) {
                    $numOfModules *= 2;
                    $modulesId = $modulesId->merge($modulesId);
                    $splitUser = $studentAttendModal->split($numOfModules);
                }
            }

            for ($j = 0; $j < $numOfModules; $j++) {
                $group = new Group();
                $group->name = 'expert' . ($j + 1);
                $group->type = 'expert';
                $group->topic()->associate($topicModuleModal->id);
                $group->module()->associate($modulesId[$j]);
                $group->save();

                $userToPop = $splitUser->pop();

                for ($i = 0; $i < count($userToPop); $i++) {
                    $group->users()->attach($userToPop[$i]->id);
                }
                $topicModuleModal->update([
                    'is_expert_form' => 1
                ]);
            }
        }
        $expertGroupUserModal = $topicModuleModal->groups()
            ->with('users.attendances')
            ->where('type', '=', 'expert')
            ->with('module')
            ->get();


        MoveExpertSession::dispatch();


        return inertia('Session/Lecturer/Expert',
            compact('topicModuleModal', 'studentAttendModal', 'studentAbsentModal', 'expertGroupUserModal'));

    }

    public function lecturerJigsawSession(Request $request) //topic_id
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        $expertGroupModal = $topicModuleModal->groups()
            ->where('type', '=', 'expert')
            ->with('users')
            ->withCount('users')
            ->get();
        $expertUserModal = Topic::find($request->input('topic_id'))->groups()
            ->where('type', '=', 'expert')
            ->with('users')
            ->get()
            ->pluck('users')
            ->flatten();

        $remainderUser = collect();
        $lessGroup = $expertGroupModal->min('users_count');
        for ($i = 0; $i < count($expertGroupModal); $i++) {
            if ($expertGroupModal[$i]->users_count > $lessGroup) {
                $user = $expertGroupModal[$i]->users()->get()->pop();
                $remainderUser->push($user);
                $expertUserModal = $expertUserModal->reject(function ($value, $key) use ($user) {
                    return $value->id == $user->id;
                });
            }
        }

        $numOfModules = $topicModuleModal->no_of_modules;
        $modulesId = $topicModuleModal->modules->pluck('id');

        (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

        if ($totalGroup % 2 === 0 && $numOfModules <= 3) {
            $numOfModules *= 2;
            $totalGroup /= 2;
            $modulesId = $modulesId->merge($modulesId);
        }
        $splitUser = $expertUserModal->split($numOfModules);

        $loop = count($splitUser[0]);
        if ($topicModuleModal->is_jigsaw_form === 0) {
            for ($j = 0; $j < $loop; $j++) {
                $group = new Group();
                $group->name = 'jigsaw' . ($j + 1);
                $group->type = 'jigsaw';
                $group->topic()->associate($topicModuleModal->id);
                $group->save();
                for ($k = 0; $k < $numOfModules; $k++) {
                    $userToPop = $splitUser[$k]->pop();
                    $userModuleId = $userToPop->groups()
                        ->where('topic_id', '=', $topicModuleModal->id)
                        ->first()->module_id;
                    $group->users()->attach($userToPop);
                    $group->users()->updateExistingPivot($userToPop->id, [
                        'module_id' => $userModuleId,
                        'module_name' => Module::find($userModuleId)->name,
                    ]);
                }
                if ($remainderUser !== null) {
                    $userToPop = $remainderUser->pop();
                    if ($userToPop !== null) {
                        $userModal = User::find($userToPop->id);
                        $userModuleId = $userModal->groups()
                            ->where('topic_id', '=', $topicModuleModal->id)
                            ->first()->module_id;
                        $group->users()->attach($userToPop);
                        $group->users()->updateExistingPivot($userModal->id, [
                            'module_id' => $userModuleId,
                            'module_name' => Module::find($userModuleId)->name,
                        ]);
                    }
                }
            }

            while ($remainderUser->isNotEmpty()) {
                $jigsawGroup = $topicModuleModal->groups()
                    ->where('type', '=', 'jigsaw')
                    ->withCount('users')
                    ->orderBy('users_count', 'asc')
                    ->first();
                $jigsawGroup->users()->attach($remainderUser->pop());
            }
            $topicModuleModal->update([
                'is_jigsaw_form' => 1
            ]);
        }

        $jigsawGroupUserModal = $topicModuleModal->groups()
            ->where('type', '=', 'jigsaw')
            ->with('users', function ($q) {
                $q->withPivot('module_id')
                    ->withPivot('module_name');
            })
            ->get();

        MoveJigsawSession::dispatch();

        $optionModule = $topicModuleModal->option()->first();

        $tm = [];
        for ($l = 0; $l < $topicModuleModal->no_of_modules; $l++) {
            $tm[$l] = $optionModule->getAttribute('tm' . ($l + 1));
        }

        return inertia('Session/Lecturer/Jigsaw', compact('topicModuleModal',
            'jigsawGroupUserModal', 'studentAttendModal', 'studentAbsentModal', 'tm'));
    }

    private function initiateStudentSessionModal($topic_id, $groupType)
    {
        $topicModal = Topic::find($topic_id);
        $groupUserModal = Group::whereHas('users', function ($query) use ($topicModal) {
            $query->where('user_id', Auth::id());
            $query->where('topic_id', '=', $topicModal->id);
        })->where('type', '=', $groupType)->with('users')->first();

//        $userModal = Group::find($groupModal->id)->users()->get();
        return [$topicModal, $groupUserModal];
    }

    private function changeAttendance($topic_id)
    {
        $topicModal = Topic::find($topic_id);

        $a = $topicModal->attendances()->get();
        $b = Auth::user()->attendances()->get();
        $c = collect();
        $c = $c->merge($a)->merge($b)->duplicates()->values();

        foreach ($c as $value) {
            $value->update([
                'attend_status' => 'present'
            ]);
        }
        broadcast(new StudentAttendance(Auth::user()->name . ' is present'));
    }

    public function studentSessionIndex(Request $request) //topic_id, time
    {
        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        return inertia('Session/Index', compact('topicModuleModal'));
    }

    public function studentExpertSession(Request $request) //topic_id
    {

        $attendance = Auth::user()->attendances()->where('topic_id', '=', $request->input('topic_id'))->where('attend_status', '=', 'absent')->exists();

        $topicModal = Topic::find($request->input('topic_id'));
        if ($attendance) {
            $this->changeAttendance($topicModal->id);
            list($topicModal, $groupUserModal) =
                $this->initiateStudentSessionModal($request->input('topic_id'), 'expert');
            $this->modifiedGroup($topicModal->id, 'expert', Auth::id());

        } else {
            list($topicModal, $groupUserModal) =
                $this->initiateStudentSessionModal($request->input('topic_id'), 'expert');
        }

        $moduleModal = $groupUserModal->module()->first();
        return inertia('Session/Student/Expert',
            compact('topicModal', 'groupUserModal', 'moduleModal'));

    }

    public function studentJigsawSession(Request $request)
    {
        $attendance = Auth::user()->attendances()->where('topic_id', '=', $request->input('topic_id'))->where('attend_status', '=', 'absent')->exists();

        $topicModal = Topic::find($request->input('topic_id'));
        if ($attendance) {
            $this->changeAttendance($topicModal->id);
            list($topicModal, $groupUserModal) =
                $this->initiateStudentSessionModal($request->input('topic_id'), 'jigsaw');
            $this->modifiedGroup($topicModal->id, 'jigsaw', Auth::id());


        } else {
            list($topicModal, $groupUserModal) =
                $this->initiateStudentSessionModal($request->input('topic_id'), 'jigsaw');
        }
        return inertia('Session/Student/Jigsaw',
            compact('topicModal', 'groupUserModal'));
    }

    private function modifiedGroup($topic_id, $groupType, $userId)
    {
        $groupModalCount = Topic::find($topic_id)->groups()
            ->where('type', '=', $groupType)
            ->with('users')
            ->withCount('users')
            ->get();

        $minUser = $groupModalCount->min('users_count');
        $group = $groupModalCount->where('users_count', '=', $minUser)->first();
        $group->users()->attach($userId);
    }

    public function updateTime(Request $request) //time related, sessionType
    {
//        dd($request->all());
        $minuteCounter = $request->input('minuteCounter');
        $secondCounter = $request->input('secondCounter');
        $transitionMinuteCounter = $request->input('transitionMinuteCounter');
        $transitionSecondCounter = $request->input('transitionSecondCounter');

        if ($request->input('sessionType') === 'expert') {
            UpdateExpertSession::dispatch($minuteCounter, $secondCounter,
                $transitionMinuteCounter, $transitionSecondCounter);
        } else {
            $moduleMinuteCounter = $request->input('moduleMinuteCounter');
            $moduleSecondCounter = $request->input('moduleSecondCounter');
            $moduleNumber = $request->input('moduleNumber');

            UpdateJigsawSession::dispatch($minuteCounter, $secondCounter,
                $transitionMinuteCounter, $transitionSecondCounter,
                $moduleMinuteCounter, $moduleSecondCounter, $moduleNumber);

        }
    }

    public function lecturerEndSession(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));

        EndSession::dispatch();

        $topicModal->update([
            'is_complete' => 1,
        ]);

        if (Auth::user()->wizard_status === 'onStartSession') {
            Auth::user()->update([
                'wizard_status' => 'onPublishAssessment'
            ]);
        }

        $classroom = $topicModal->classroom()->first();
        return redirect()->route('classroom.show', $classroom)
            ->with('alertMessage', 'Session Successfully Ended');
    }

    public function studentEndSession(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $topicModal->update([
            'is_complete' => true,
        ]);

        $classroom = $topicModal->classroom()->first();


        return redirect()->route('classroom.show', $classroom)
            ->with('alertMessage', 'Your Lecturer Successfully Ended the session');
    }
}
