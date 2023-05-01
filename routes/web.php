<?php

use App\Events\StudentAttendance;
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

Route::get('/module/editIndex', [ModuleController::class, 'editIndex'])->name('module.editIndex');
Route::post('/module/editIndex', [ModuleController::class, 'updateIndex'])->name('module.updateIndex');
Route::get('/option/editIndex', [OptionController::class, 'editIndex'])->name('option.editIndex');
Route::post('/option/editIndex', [OptionController::class, 'updateIndex'])->name('option.updateIndex');

Route::resource('classroom', ClassroomController::class);
Route::resource('topic', TopicController::class);
Route::resource('module', ModuleController::class);
Route::resource('assessment', AssessmentController::class);
Route::resource('question', QuestionController::class);
Route::resource('option', OptionController::class);


//Route::get('/pusher', [SessionController::class,'fetchExpertPusher'])->name('fetch.expert');
Route::post('/pusher', [SessionController::class, 'sendExpertPusher'])->name('send.expert');

//Route::post('/fetchAbsentStudentE', [SessionController::class, 'fetchExpertAbsentStudent'])->name('fetch.absent.expertStudent');

Route::get('/event', function () {
    event(new StudentAttendance('hello world'));
});

Route::get('listen', function () {
    return \inertia('Test');
});

Route::get('verify', [TopicController::class, 'verify'])->name('topic.verify');
Route::post('verify', [TopicController::class, 'storeVerify'])->name('topic.storeVerify');

Route::get('dashboard', [ClassroomController::class, 'index'])->name('dashboard');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::group(['prefix' => 'session'], function () {
    Route::get('studentExpert', [SessionController::class, 'AstudentExpert'])->name('student.expert');
    Route::get('studentJigsaw', [SessionController::class, 'AstudentJigsaw'])->name('student.jigsaw');
    Route::get('lecturerExpert', [SessionController::class, 'AlecturerExpert'])->name('lecturer.expert');
    Route::get('lecturerJigsaw', [SessionController::class, 'AlecturerJigsaw'])->name('lecturer.jigsaw');
    Route::get('start', [SessionController::class, 'lecturerIndexSession'])->name('lecturer.session.index');
    Route::get('expert', [SessionController::class, 'lecturerExpertSession'])->name('lecturer.session.expert');
    Route::get('jigsaw', [SessionController::class, 'lecturerJigsawSession'])->name('lecturer.session.jigsaw');
    Route::get('student/start', [SessionController::class, 'studentSessionIndex'])->name('student.session.index');
    Route::get('student/expert', [SessionController::class, 'studentExpertSession'])->name('student.session.expert');
    Route::get('student/jigsaw', [SessionController::class, 'studentJigsawSession'])->name('student.session.jigsaw');
});

Route::get('assessment-student-index', [AssessmentController::class, 'studentAssessmentIndex'])->name('student.assessment.index');
Route::post('assessment-publish', [AssessmentController::class, 'publishAssessment'])->name('assessment.publish');
Route::get('assessment-student-session', [AssessmentController::class, 'studentSessionIndex'])->name('student.assessment.session');
Route::post('assessment-student-check-answer', [AssessmentController::class, 'studentCheckAnswer'])->name('student.assessment.check.answer');

Route::get('displayGroup', [TestController::class, 'displayGroup'])->name('display.group');
Route::get('displayGroupM', [TestController::class, 'displayGroupModified'])->name('display.modifiedGroup');

Route::group(['prefix' => 'LecturerSide'], function () {

    Route::inertia('/CreateOption', 'Lecturer/CreateTopic/Option')->name('lect.create.option');
});

Route::group(['prefix' => 'StudentSide'], function () {
    Route::inertia('/ExpertSession', 'Student/Session/ExpertSession')->name('stud.session.expert');
});

//Route::inertia('profileTest', 'Profile')->name('profileIndex');

require __DIR__ . '/auth.php';
