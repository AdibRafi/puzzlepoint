<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Classroom;
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
        $data = Assessment::find(2)->users()->get();

//        $data = Assessment::find(1)->questions()->get();
//        $data = Question::find(3)->assessment()->get();

        return inertia('Test',compact('data'));
    }
}
