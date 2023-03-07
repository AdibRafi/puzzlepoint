<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = Classroom::all();
        return inertia('Test',compact('data'));
    }
}
