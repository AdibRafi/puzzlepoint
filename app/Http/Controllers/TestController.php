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
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
//        $data = auth()->user()->attendances()->get();
        $data = auth()->user();

//        $data = Assessment::find(1)->questions()->get();
//        $data = Question::find(3)->assessment()->get();

        return inertia('Test', compact('data'));
    }

    public function store(Request $request) //topic_id / groupMethod / timeMethod / tm{}
    {
        $topicModal = Topic::find($request->input('topic_id'));
        $modulesId = $topicModal->modules()->pluck('id');
        //distribution logic
        $usersId = $topicModal->getUsers()->pluck('id')->shuffle();
//        dd(count($usersId));
//        dd($topicModal->id);
//        dd($modulesId);


        //START JG logic

//        $test = Group::find(21)->users()->pluck('id');

        (int)$totalGroup = floor(count($usersId) / $topicModal->no_of_modules);

        //CHECK NUMBER USER AND MODULE ID BEFORE EXECUTE
        $a = $usersId->split($totalGroup); //if student = 20, 4x5
        //todo: check on how to insert database for jigsaw group link with module
        for ($i = 0; $i < $totalGroup; $i++) {
            $group = new Group();
            $group->name = 'jigsaw' . $i;
            $group->group_type = 'jigsaw';
            $group->topic()->associate($topicModal->id);
            $group->save();

            $group->users()->attach($a[$i]);
        }

        for ($i = 0; $i < count($a[0]); $i++) {
            $group = new Group();
            $group->name = 'expert' . $i;
            $group->group_type = 'expert';
            $group->topic()->associate($topicModal->id);
            $group->module()->associate($modulesId[$i]);
//            Module::find($modulesId[$i])->group()->associate($group->id);
            $group->save();
            for ($j = 0; $j < count($a); $j++) {
                $group->users()->attach($a[$j][$i]);
            }
        }
//            $group->modules()->attach($modulesId[$i], ['group_type' => 'expert']);
//        dd($usersId);
        $r = User::find(1)->groups()->pluck('id');
        dd($r);
//        dd(Group::find(10)->modules()->wherePivot('group_type', '=', 'expert')->get());
//        dd(Group::find(80)->users()->pluck('name'));

        dd($topicModal->modules()->pluck('id'));


        dd($usersId);
        return redirect()->route('test.index');
    }
}
