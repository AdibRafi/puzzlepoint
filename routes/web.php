<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProfileAController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TopicController;
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

Route::get('test', [TestController::class, 'index'])->name('test.index');
Route::post('test', [TestController::class, 'store'])->name('test.store');


//Route::inertia('dashboard', 'Dashboard')->name('dashboard');
Route::resource('classroom', ClassroomController::class);
Route::resource('topic', TopicController::class);
Route::resource('module', ModuleController::class);
Route::resource('assessment', AssessmentController::class);
Route::resource('answer', AnswerController::class);
Route::resource('question', QuestionController::class);
Route::resource('option', OptionController::class);

Route::get('verify', [TopicController::class, 'verify'])->name('topic.verify');
Route::post('verify', [TopicController::class, 'storeVerify'])->name('topic.storeVerify');

Route::get('dashboard', [ClassroomController::class, 'index'])->name('dashboard');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::group(['prefix' => 'session'], function () {
    Route::get('studentExpert', [SessionController::class, 'studentExpert'])->name('student.expert');
    Route::get('studentJigsaw', [SessionController::class, 'studentJigsaw'])->name('student.jigsaw');
    Route::get('lecturerExpert', [SessionController::class, 'lecturerExpert'])->name('lecturer.expert');
    Route::get('lecturerJigsaw', [SessionController::class, 'lecturerJigsaw'])->name('lecturer.jigsaw');
});

Route::get('displayGroup', [TestController::class, 'displayGroup'])->name('display.group');
Route::get('displayGroupM',[TestController::class,'displayGroupModified'])->name('display.modifiedGroup');

Route::group(['prefix' => 'LecturerSide'], function () {

    Route::inertia('/CreateOption', 'Lecturer/CreateTopic/Option')->name('lect.create.option');
});

Route::group(['prefix' => 'StudentSide'], function () {
    Route::inertia('/ExpertSession', 'Student/Session/ExpertSession')->name('stud.session.expert');
});

//Route::inertia('profileTest', 'Profile')->name('profileIndex');

require __DIR__ . '/auth.php';
