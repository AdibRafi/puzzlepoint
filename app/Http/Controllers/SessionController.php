<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function studentExpert(){
        $user = auth()->user();
        $groupModal = $user->groups()->get();

        dd($groupModal);

    }
}
