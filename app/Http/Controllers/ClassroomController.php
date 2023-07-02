<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassroomRequest;
use App\Imports\UserImport;
use App\Models\Assessment;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Module;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //check assessment date
        $timeFromDb = Assessment::all()->where('publish_end', '>=', Carbon::now())
            ->where('is_publish', '=', 1);
        $timeFromDb->each(function ($query) {
            $query->update([
                'is_complete' => 1,
            ]);
        });


//        $timeFromDb = Carbon::parse($timeFromDb);
//        dd($timeFromDb);

        $classroomData = auth()->user()->classrooms()->get();

        return inertia('Classroom/Index', compact('classroomData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Classroom/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->type === 'student') {
            $request->validate([
                'join_code' => 'required'
            ], [
                'join_code.required' => 'Please Enter the join code'
            ]);

            $classroom = Classroom::where('join_code', '=', $request->input('join_code'))
                ->first();
            if ($classroom === null) {
                return back()->with('warningMessage', 'The code you enter is not in database');
            } else {
                if ($classroom->users()->where('id', '=', Auth::id())->exists()) {
                    return back()->with('warningMessage', 'You already join this class');
                } else {
                    $classroom->users()->attach(Auth::user());
                    return redirect()->route('classroom.index')
                        ->with('alertMessage', 'Class successfully added');
                }
            }
        } else {
            $request->validate([
                'name' => 'required',
                'subject_code' => 'required',
            ], [
                'name.required' => 'Please Fill in Name',
                'subject_code.required' => 'Please Fill in Subject Code'
            ]);
            $classroom = new Classroom();
            $classroom->name = $request->input('name');
            $classroom->subject_code = $request->input('subject_code');
            $classroom->join_code = Str::random(6);
            $classroom->is_new = 1;
            $classroom->save();

            auth()->user()->classrooms()->attach($classroom->id);

            if (Auth::user()->wizard_status === 'onCreateClassroom') {
                Auth::user()->update([
                    'wizard_status' => 'onAddStudent'
                ]);
                User::where('type', '=', 'student')->each(function ($user) use ($classroom) {
                    $user->classrooms()->attach($classroom->id);
                });
            }
        }

        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $classroom->update([
            'is_new' => 0,
        ]);

        $topicModal = $classroom->topics()
            ->where('is_complete', '=', 0)
            ->orWhere('is_complete', '=', 1)
            ->whereHas('assessment', function ($query) {
                $query->where('is_complete', '=', 0);
            })
            ->get();

//        dd($topicModal);
        $topicArchiveModal = $classroom->topics()
            ->where('is_complete', '=', 1)
            ->whereHas('assessment', function ($query) {
                $query->where('is_complete', '=', 1);
            })->get();

        $studentModal = $classroom->users()
            ->where('type', '=', 'student')
            ->get();
        return inertia('Classroom/Show',
            compact('classroom', 'topicModal',
                'topicArchiveModal', 'studentModal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        return inertia('Classroom/Edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        //todo: detach topic n its relationship
        $classroom->users()->detach();
        $classroom->delete();

        return redirect()->route('classroom.index')
            ->with('alertMessage', 'Classroom deleted successfully');
    }

    public function addStudentCreate(Request $request) //classroom_id
    {
//        dd($request->all());
        $classroomModal = Classroom::find($request->input('classroom_id'))
            ->loadCount(['users' => function ($q) {
                $q->where('type', '=', 'student');
            }]);
        if ((Auth::user()->is_wizard_complete === 0 && Auth::user()->wizard_status === 'onAddStudent') && $classroomModal->users_count >= 20) {
            Auth::user()->update([
                'wizard_status' => 'onCreateTopic',
            ]);
            Auth::user()->fresh();
        }

        return inertia('Classroom/AddStudent', compact('classroomModal'));
    }

    public function addStudentStore(Request $request) //name, email,gender, file_path, classroom_id
    {
//        dd($request->all());
        $classroomModal = Classroom::find($request->input('classroom_id'));
        $classroom_id = $request->input('classroom_id');

        if (Auth::user()->is_wizard_complete === 0 &&
            $classroomModal->users()->where('type', '=', 'student')->count() >= 20) {
            Auth::user()->update([
                'wizard_status' => 'onCreateTopic',
            ]);
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $file->move('Student', $file->getClientOriginalName());
            Excel::import(new UserImport(), 'Student/' . $file->getClientOriginalName());
            $userArr = Excel::toArray(new UserImport(),
                'Student/' . $file->getClientOriginalName());

            for ($i = 0; $i < count($userArr[0]); $i++) {
                if (!$classroomModal->users()
                    ->where('email', '=', $userArr[0][$i][1])
                    ->exists()) {
                    $classroomModal->users()
                        ->attach(
                            User::where('email', '=', $userArr[0][$i][1])
                                ->first()->id
                        );
                }
            }
            return redirect()->route('classroom.show', $classroomModal)
                ->with('alertMessage', 'Students Successfully added');
        } else {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'gender' => 'required',
            ], [
                'name.required' => 'Please fill in the Student Name',
                'email.required' => 'Please fill in the Student Email',
                'gender.required' => 'Please choose gender of the Student'
            ]);

            if ($classroomModal->users()
                ->where('email', '=', $request->input('email'))
                ->exists()) {
                return redirect()->route('classroom.add-student.create', compact('classroom_id'))
                    ->with('warningMessage', 'This student already join this class');
            } else {
                $checkUser = User::where('email', '=', $request->input('email'));
                if (!$checkUser->exists()) {
                    $checkUser = User::create([
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'gender' => $request->input('gender'),
                        'type' => 'student',
                        'is_wizard_complete' => 1,
                    ]);
                }
                $classroomModal->users()->attach($checkUser);
                return redirect()->route('classroom.add-student.create', compact('classroom_id'))
                    ->with('alertMessage', 'Student ' . $checkUser->name . ' Successfully added');
            }
        }
    }

    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            $header = fgetcsv($handle);
            while ($row = fgetcsv($handle)) {
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);
//        $header = null;
//        $data = array();
//        if (($handle = fopen($filename, 'r')) !== false) {
//            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
//                if (!$header)
//                    $header = $row;
//                else
//                    $data[] = array_combine($header, $row);
//            }
//            fclose($handle);
//        }

        return $data;
    }
}
