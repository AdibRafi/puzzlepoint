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

//        dd($timeFromDb);
//        dd($timeFromDb->subMinutes(15));


        $numOfModule = $topicModuleModal->no_of_modules;
        $minStudent = null;
        if ($numOfModule === 2) {
            $minStudent = 4;
        } elseif ($numOfModule === 3) {
            $minStudent = 6;
        } elseif ($numOfModule === 4) {
            $minStudent = 8;
        } elseif ($numOfModule === 5) {
            $minStudent = 10;
        } elseif ($numOfModule === 6) {
            $minStudent = 12;
        }

        if (Auth::user()->type === 'student' && $topicModuleModal->is_start === 0) {
            $this->changeAttendance($topicModuleModal->id);
        }

        return inertia('Session/Index', compact('topicModuleModal',
            'studentAttendModal', 'studentAbsentModal', 'timeAddBuffer', 'minStudent'));

    }

    public function lecturerExpertSession(Request $request) //topic_id //Time
    {

        list($topicModuleModal, $studentAttendModal, $studentAbsentModal) =
            $this->initiateTopicModuleStudentModal($request->input('topic_id'));

        $topicModuleModal->update([
            'is_start' => 1,
        ]);

        if ($topicModuleModal->is_buffer_add === 0) {
            $timeFromDb = Carbon::parse($topicModuleModal->date_time);
            $timeNow = Carbon::now();

            $diff = $timeNow->diff($timeFromDb);

            $remainderTime = ($topicModuleModal->max_buffer) - $diff->i;
            if ($remainderTime > 0) {
                $splitTime = floor($remainderTime / 2);
                $topicModuleModal->update([
                    'max_session' => ($topicModuleModal->max_session + $remainderTime),
                    'max_time_expert' => ($topicModuleModal->max_time_expert + $splitTime),
                    'max_time_jigsaw' => ($topicModuleModal->max_time_jigsaw + $splitTime),
                ]);
                $splitTimeTm = floor($remainderTime / $topicModuleModal->no_of_modules);
                $topicModuleModal->option()->update([
                    'tm1' => ($topicModuleModal->option()->first()->tm1 + $splitTimeTm),
                    'tm2' => ($topicModuleModal->option()->first()->tm2 + $splitTimeTm),
                    'tm3' => ($topicModuleModal->option()->first()->tm3 + $splitTimeTm),
                    'tm4' => ($topicModuleModal->option()->first()->tm4 + $splitTimeTm),
                    'tm5' => ($topicModuleModal->option()->first()->tm5 + $splitTimeTm),
                    'tm6' => ($topicModuleModal->option()->first()->tm6 + $splitTimeTm),
                ]);
            }
            $topicModuleModal->update([
                'is_buffer_add' => 1,
            ]);
        }


        if ($topicModuleModal->is_expert_form === 0) {

            if ($topicModuleModal->option()->first()->group_distribution === 'random') {
                $doneShuffleStudent = $studentAttendModal->shuffle();
            } elseif ($topicModuleModal->option()->first()->group_distribution === 'genderFixed') {
                $doneShuffleStudent = $studentAttendModal->shuffle()->sortBy('gender');

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

            if ($totalGroup % 2 === 0 && $numOfModules <= 3 && count($studentAttendModal) > 12) {
                $numOfModules *= 2;
//                $totalGroup /= 2;
                $modulesId = $modulesId->merge($modulesId);
            }

            $splitUser = $doneShuffleStudent->split($numOfModules);

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
        if ($topicModuleModal->getAttendStudents()->count() % $topicModuleModal->no_of_modules !== 0) {
            $lessGroup = $expertGroupModal->min('users_count');
            for ($i = 0; $i < count($expertGroupModal); $i++) {
                if ($expertGroupModal[$i]->users_count > $lessGroup) {
                    $user = $expertGroupModal[$i]->users()->get()->pop();
                    $remainderUser->push($user);
                    $expertUserModal = $expertUserModal->reject(function ($value, $key) use ($user) {
                        return $value->id === $user->id;
                    });
                }
            }
        }

        $numOfModules = $topicModuleModal->no_of_modules;
        $modulesId = $topicModuleModal->modules->pluck('id');

        (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

        if ($totalGroup % 2 === 0 && $numOfModules <= 3 && count($studentAttendModal) > 12) {
            $numOfModules *= 2;
            $totalGroup /= 2;
            $modulesId = $modulesId->merge($modulesId);
        }
        $splitUser = $expertUserModal->split($numOfModules);

        $loop = count($splitUser[0]);

        if (count($splitUser[0]) > 7) {
            while (count($splitUser[0]) > 7) {
                $numOfModules *= 2;
                $modulesId = $modulesId->merge($modulesId);
                $splitUser = $studentAttendModal->split($numOfModules);
            }
        }
        $addGroup = $splitUser->count() / $topicModuleModal->no_of_modules;

        if ($addGroup >= 1) {
            for ($o = 0; $o < $topicModuleModal->no_of_modules; $o++) {
                $a = collect();
                for ($n = 0; $n < $addGroup; $n++) {
                    $a = $a->merge($splitUser[($n * $topicModuleModal->no_of_modules) + $o]);
                }
                $splitUser[$o] = $a;
            }
            $splitUser = $splitUser->slice(0, $topicModuleModal->no_of_modules);
        }

        if ($topicModuleModal->is_jigsaw_form === 0) {
            for ($j = 0; $j < $loop; $j++) {
                $group = new Group();
                $group->name = 'jigsaw' . ($j + 1);
                $group->type = 'jigsaw';
                $group->topic()->associate($topicModuleModal->id);
                $group->save();
                for ($k = 0; $k < $topicModuleModal->no_of_modules; $k++) {
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
            $query->where('user_id', Auth::id())
                ->where('topic_id', '=', $topicModal->id);
        })->where('type', '=', $groupType)
            ->with('users', function ($q) {
                $q->withPivot('module_id')
                    ->withPivot('module_name');
            })->first();

        $moduleModal = $topicModal->modules()->get();
//        $userModal = Group::find($groupModal->id)->users()->get();
        return [$topicModal, $groupUserModal, $moduleModal];
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


        return inertia('Session/Student/Index', compact('topicModuleModal'));
    }

    public function studentExpertSession(Request $request) //topic_id
    {

        $attendance = Auth::user()->attendances()
            ->where('topic_id', '=', $request->input('topic_id'))
            ->where('attend_status', '=', 'absent')
            ->exists();

        $topicModal = Topic::find($request->input('topic_id'));

        if ($attendance) {
            $this->changeAttendance($topicModal->id);
            $this->modifiedGroup($topicModal->id, 'expert', Auth::id());
        }
        list($topicModal, $groupUserModal) =
            $this->initiateStudentSessionModal($request->input('topic_id'), 'expert');

        $moduleModal = $groupUserModal->module()->exists() ? $groupUserModal->module()->first() : null;
        return inertia('Session/Student/Expert',
            compact('topicModal', 'groupUserModal', 'moduleModal'));

    }

    public function studentJigsawSession(Request $request)
    {
        $attendance = Auth::user()->attendances()->where('topic_id', '=', $request->input('topic_id'))->where('attend_status', '=', 'absent')->exists();

        $topicModal = Topic::find($request->input('topic_id'));
        if ($attendance) {
            $this->modifiedGroup($topicModal->id, 'jigsaw', Auth::id());
            $this->changeAttendance($topicModal->id);
        }
        list($topicModal, $groupUserModal, $moduleModal) =
            $this->initiateStudentSessionModal($request->input('topic_id'), 'jigsaw');
        return inertia('Session/Student/Jigsaw',
            compact('topicModal', 'groupUserModal', 'moduleModal'));
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
        if ($groupType === 'jigsaw') {
            $group->users()->updateExistingPivot($userId, [
                'module_name' => 'Late Comers'
            ]);
        }
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

        $topicModal->assessment()->update([
            'is_publish' => 1,
        ]);

        if (Auth::user()->wizard_status === 'onStartSession') {
            Auth::user()->update([
                'wizard_status' => 'onEndAssessment'
            ]);
        }

//        $classroom = $topicModal->classroom()->first();
        return redirect()->route('topic.show', $topicModal)
            ->with('alertMessage', 'Session Successfully Ended');
    }

    public function studentEndSession(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
//        $topicModal->update([
//            'is_complete' => 1,
//        ]);


        return redirect()->route('topic.show', $topicModal)
            ->with('alertMessage', 'Your Lecturer Successfully Ended the Session');
    }
}
