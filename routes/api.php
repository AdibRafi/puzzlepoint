<?php

use App\Events\MoveExpertSession;
use App\Events\MoveJigsawSession;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('student-attendance', [EventController::class, 'studentAttendance'])->name('event.student-attendance');

Route::get('move-expert-session', function () {
    MoveExpertSession::dispatch();
})->name('event.move-expert-session');

Route::get('move-jigsaw-session', function () {
    MoveJigsawSession::dispatch();
})->name('event.move-jigsaw-session');

Route::get('time-session', [EventController::class, 'timeSession'])->name('event.time-session');
