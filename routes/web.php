<?php

use App\Http\Controllers\ProfileAController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Models\Question;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboardA', function () {
    return Inertia::render('Archives/DashboardA');
})->middleware(['auth', 'verified'])->name('dashboardA');

Route::middleware('auth')->group(function () {
    Route::get('/profileA', [ProfileAController::class, 'edit'])->name('profileA.edit');
    Route::patch('/profileA', [ProfileAController::class, 'update'])->name('profileA.update');
    Route::delete('/profileA', [ProfileAController::class, 'destroy'])->name('profileA.destroy');
});

//Route::get('/test', function () {
//    return view('test');
//});

Route::get('question', [QuestionController::class, 'index']);

Route::inertia('dashboard', 'Dashboard')->name('dashboard');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');

Route::group(['prefix' => 'LecturerSide'], function () {
    //todo: rename create -> topic related stuff (with directory)
    Route::inertia('/CreateTopic', 'Lecturer/CreateTopic/CreateTopic')->name('lect.create.topic');
    Route::inertia('/CreateModule', 'Lecturer/CreateTopic/CreateModule')->name('lect.create.module');
    Route::inertia('/CreateOption', 'Lecturer/CreateTopic/CreateOption')->name('lect.create.option');
    Route::inertia('/CreateVerify', 'Lecturer/CreateTopic/CreateVerify')->name('lect.create.verify');

    Route::inertia('/CreateAssessment', 'Lecturer/Assessment/CreateAssessment')->name('lect.assessment.create');

    Route::inertia('/ExpertSession', 'Lecturer/Session/ExpertSession')->name('lect.session.expert');
});

//Route::inertia('profileTest', 'Profile')->name('profileIndex');

require __DIR__ . '/auth.php';
