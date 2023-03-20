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
//        dd($usersId);
        //EG logic
//        $a = $usersId->split(4);
//        for ($i = 0; $i < count($modulesId); $i++) {
//            $group = new Group();
//            $group->expert_group = 'test' . $i;
//            $group->save();
//            $group->modules()->attach($modulesId[$i],['group_type'=>'expert']);
//            $group->users()->attach($a[$i]);
//        }
        //START JG logic

        //JG
        $test = Module::find(1)->groups()->wherePivot('group_type', '=', 'jigsaw')->pluck('group_id');

//        $test = Group::find(21)->users()->pluck('id');

        (int)$totalGroup = floor(count($usersId) / $topicModal->no_of_modules);

        $a = $usersId->split($totalGroup); //if student = 20, 4x5
//        dd(count($a[0]) - 1);
        //todo: check on how to insert database for jigsaw group link with module
        for ($i = 0; $i < $totalGroup; $i++) {
            $group = new Group();
            $group->jigsaw_group = 'jigsaw' . $i;
            $group->save();
            for ($j = 0; $j < count($modulesId); $j++) {
                $group->modules()->attach($modulesId[$j], ['group_type' => 'jigsaw']);
            }
            $group->users()->attach($a[$i]);
        }
//
//        for ($i = 0; $i < count($a[0]); $i++) {
//            $group = new Group();
//            $group->expert_group = 'expert' . $i;
//            $group->save();
//            $group->modules()->attach($modulesId[$i], ['group_type' => 'expert']);
//            for ($j = 0; $j < count($a); $j++) {
//                $group->users()->attach($a[$j][$i]);
//            }
//        }
//        dd($usersId);
        $r = User::find(2)->groups()->pluck('id');
        dd(Group::find(88)->modules()->wherePivot('group_type','=','expert')->get());
        dd(Group::find(80)->users()->pluck('name'));


        $b = $usersId->split(5);
        for ($i = 0; $i < count($b); $i++) {
            $group = new Group();
            $group->jigsaw_group = 'test' . $i;
            $group->save();
            for ($j = 0; $j < count($modulesId); $j++) {
                $group->modules()->attach($modulesId[$j], ['group_type' => 'jigsaw']);
            }
            $group->users()->attach($b[$i]);
        }
        dd($topicModal->modules()->pluck('id'));


        dd($usersId);
        return redirect()->route('test.index');
    }
}
