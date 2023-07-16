<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Material;
use App\Models\Module;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\TwoFactorVerification;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Collection;
use function Sodium\add;

class TestController extends Controller
{
    public function index()
    {
//        $data = auth()->user()->attendances()->get();
        $user = User::where('email', '=', 'test@gmail.com')->exists();

        $data = auth()->user();
        $moduleData = Topic::find(1)->modules()->get();

        return inertia('Test', compact('data', 'moduleData'));
    }

    public function store(Request $request)
    {
        $topicModuleModal = Topic::find(1)->load('modules');
        $studentAttendModal = $topicModuleModal->getAttendStudents();
        $studentAbsentModal = $topicModuleModal->getAbsentStudents();

        if ($topicModuleModal->is_expert_form === 0) {

            if ($topicModuleModal->option()->first()->group_distribution === 'random') {
                $doneShuffleStudent = $studentAttendModal->shuffle();
            } elseif ($topicModuleModal->option()->first()->group_distribution === 'genderFixed') {
                $doneShuffleStudent = $studentAttendModal->shuffle()->sortBy('gender');
//                dd($doneShuffleStudent);
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

            if ($totalGroup % 2 === 0 && $numOfModules <= 3 && count($studentAttendModal) > 6) {
                $numOfModules *= 2;
//                $totalGroup /= 2;
                $modulesId = $modulesId->merge($modulesId);
            }
//            dd($doneShuffleStudent->pluck('gender'));
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

                for ($m = 0; $m < count($userToPop); $m++) {
                    $group->users()->attach($userToPop[$m]->id);
                }
                $topicModuleModal->update(['is_expert_form' => 1]);
            }
        }
        $expertGroupUserModal = $topicModuleModal->groups()
            ->with('users.attendances')
            ->where('type', '=', 'expert')
            ->get();

        $expertGroupModal = $topicModuleModal->groups()
            ->where('type', '=', 'expert')
            ->with('users')
            ->withCount('users')
            ->get();
        $expertUserModal = Topic::find($request->input('topic_id'))->groups()->where('type', '=', 'expert')->with('users')->get()->pluck('users')->flatten();

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
//        dd($expertUserModal);
//        dd($expertGroupModal);

        $numOfModules = $topicModuleModal->no_of_modules;
        $modulesId = $topicModuleModal->modules->pluck('id');

        (int)$totalGroup = floor(count($studentAttendModal) / $topicModuleModal->no_of_modules);

        if ($totalGroup % 2 === 0 && $numOfModules <= 3 && count($studentAttendModal) > 6) {
            $numOfModules *= 2;
            $totalGroup /= 2;
            $modulesId = $modulesId->merge($modulesId);
        }

        $splitUser = $expertUserModal->split($numOfModules);

        $loop = count($splitUser[0]);

        $addGroup = 0;
//        dd($splitUser);
        if (count($splitUser[0]) > 7) {
            while (count($splitUser[0]) > 7) {
                $numOfModules *= 2;
                $modulesId = $modulesId->merge($modulesId);
                $splitUser = $studentAttendModal->split($numOfModules);
            }
        }
        $addGroup = $splitUser->count() / $topicModuleModal->no_of_modules;

//        dd($splitUser);
//        dd($addGroup);
        if ($addGroup >= 1) {
            for ($o = 0; $o < $topicModuleModal->no_of_modules; $o++) {
                $a = collect();
                for ($n = 0; $n < $addGroup; $n++) {
                    $a = $a->merge($splitUser[($n * $topicModuleModal->no_of_modules) + $o]);
                }
                $splitUser[$o] = $a;
//                dd($splitUser[$o]);
            }
            $splitUser = $splitUser->slice(0, $topicModuleModal->no_of_modules);
        }

//        dd($splitUser);
//        dd($loop);
//        $splitUser[0] = $a;
//        dd($splitUser->slice(0,$topicModuleModal->no_of_modules));

//        for ($o = 0; $o < $topicModuleModal->no_of_modules; $o++) {
//            $splitUser[$o] = $splitUser[$o]->merge($splitUser[$o + 5]);
//        }

//        dd($splitUser[0]);
//        $splitUser[0] = $splitUser[0]->merge($splitUser[5]);
//        $a = collect();
//        for ($k = 0; $k < $topicModuleModal->no_of_modules; $k++) {
//            $userToPop = $splitUser[$k]->pop();
//            $userModuleId = $userToPop->groups()
//                ->where('topic_id', '=', $topicModuleModal->id)
//                ->first()->module_id;
//            $a->add($userModuleId);
//        }

//        dd($loop);


//        if ($addGroup >= 1) {
//            for ($n = 0; $n < $addGroup; $n++) {
////                $numOfModules *= 2;
//                $modulesId = $modulesId->merge($modulesId);
//                $splitUser = $studentAttendModal->split($numOfModules);
//            }
////            dd($splitUser);
//        }

//        dd($loop);
//        dd($splitUser);

//        dd($loop);
        //todo: break the cycle if there's no splituser
        if ($topicModuleModal->is_jigsaw_form === 0) {
            for ($j = 0; $j < $loop; $j++) {
                $group = new Group();
                $group->name = 'jigsaw' . ($j + 1);
                $group->type = 'jigsaw';
                $group->topic()->associate($topicModuleModal->id);
                $group->save();
                for ($k = 0; $k < $topicModuleModal->no_of_modules; $k++) {
                    $userToPop = $splitUser[$k]->pop();
//                    if ($j === 12) {
//                        dd($userToPop);
//                    }
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
        $topicModuleModal->update(['is_jigsaw_form' => 1]);


        $jigsawGroupUserModal = $topicModuleModal->groups()
            ->where('type', '=', 'jigsaw')
            ->with('users', function ($q) {
                $q->withPivot('module_id')
                    ->withPivot('module_name');
            })
            ->get();


        $topic_id = 1;
        return redirect()->route('display.group', compact('topic_id'))
            ->with('alertMessage', 'Done create Group');
    }

    public function displayGroup(Request $request) //topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'))->load('modules');
        $expertGroupUserModal = $topicModal->groups()
            ->with('users.attendances')
            ->with('module')
            ->withCount('users')
            ->where('type', '=', 'expert')
//            ->orderBy('users_count', 'desc')
            ->get();
        $jigsawGroupUserModal = $topicModal->groups()
            ->with('users', function ($q) {
                $q->with('attendances')
                    ->withPivot('module_id')
                    ->withPivot('module_name');
            })
            ->withCount('users')
            ->where('type', '=', 'jigsaw')
//            ->orderBy('users_count', 'desc')
            ->get();
        $absentStudentModal = $topicModal->getAbsentStudents();
        $totalStudents = $topicModal->getStudents();


        return inertia('DisplayGroup', compact('topicModal', 'expertGroupUserModal', 'absentStudentModal', 'jigsawGroupUserModal',
            'totalStudents'));
    }

    public function displayGroupModified(Request $request)//topic_id
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $absentStudentModal = $topicModal->getAbsentStudents();

        $groupModalWithAbsentStudent = $topicModal->groups()
            ->where('type', '=', 'jigsaw')
            ->whereHas('users', function ($query) {
                $query->whereHas('attendances', function ($query) {
                    $query->where('attend_status', '=', 'absent');
                });
            })->get();


        $groupModal = $topicModal->groups()
            ->where('type', '=', 'jigsaw')->get();

        $groupModal = $groupModal->reject(function ($value, $key) use ($groupModalWithAbsentStudent) {
            return $groupModalWithAbsentStudent->contains($value);
        })->flatten();

        $studentWithAbsentModal = $groupModalWithAbsentStudent->pluck('users')->flatten();

        for ($i = 0; $i < count($studentWithAbsentModal); $i++) {
            $group = Group::find($groupModal[$i]->id);
            $group->users()->attach($studentWithAbsentModal[$i]);
        }

//        foreach ($studentWithAbsentModal as $value) {
//            $groupId = $groupModal->random()->id;
//            $group = Group::find($groupId);
//            $group->users()->attach($value);
//        }
        Group::destroy($groupModalWithAbsentStudent);


        dd($groupModal);
        dd($groupModalWithAbsentStudent);
        dd($absentStudentModal);


    }

    public function smsTest()
    {
        Auth::user()->notify(new TwoFactorVerification());
    }

    public function migrateRefreshSeed(Request $request) //students, modules, fixed_student, groupType
    {
//        dd($request->all());

        Artisan::call('group:seed', [
            '--students' => $request->input('students'),
            '--modules' => $request->input('modules'),
            '--fixed_student' => (int)$request->input('fixed_student'),
            '--group_type' => $request->input('group_type')
        ]);

        return back()->with('alertMessage', 'Successfully Migrate using GroupSeeder');
    }
}
