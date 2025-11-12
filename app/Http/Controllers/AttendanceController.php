<?php

namespace App\Http\Controllers;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class AttendanceController extends Controller
{

    public function __construct()
{
    \Log::info('AttendanceController hit', [
        'url' => request()->fullUrl(),
        'method' => request()->method(),
        'route' => optional(request()->route())->getName(),
        'previous' => url()->previous(),
    ]);
}
      public function index()
    {
        $months = Attendance::select('attendence_date')
                            ->orderBy('attendence_date')
                            ->get()
                            ->groupBy(function ($val) {
                                return Carbon::parse($val->attendence_date)->format('m');
                            });

        if( request()->has(['type', 'month']) ) {
            $type = request()->input('type');
            $month = request()->input('month');

            if($type == 'class') {
                $attendances = Attendance::whereMonth('attendence_date', $month)
                                     ->select('attendence_date','student_id','attendence_status','class_id')
                                     ->orderBy('class_id','asc')
                                     ->get()
                                     ->groupBy(['class_id','attendence_date']);
                return view('backend.attendance.index', compact('attendances','months'));
            }
            
        }
        $attendances = [];
        
        return view('backend.attendance.index', compact('attendances','months'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }



       public function createByTeacher($classid)
    {
        $class = Grade::with(['students','subjects','teacher'])->findOrFail($classid);
        $loggedInTeacherId = auth()->user()->teacher->id;
        if ($class->class_teacher !== $loggedInTeacherId) {
            abort(403, 'Unauthorized access — you are not the class teacher of this class.');
        }
        return view('backend.attendance.create', compact('class'));
    }
  

public function store(Request $request)
{
    try {
        Log::info('Attendance store request received', [
            'teacher_user_id' => auth()->id(),
            'teacher_id' => optional(auth()->user()->teacher)->id,
            'class_id' => $request->class_id,
            'teacher_id_input' => $request->teacher_id,
            'attendences_count' => count($request->attendences ?? []),
        ]);

        $classid    = $request->class_id;
        $teacherid  = $request->teacher_id;
        $attenddate = date('Y-m-d');

        $teacher = Teacher::findOrFail(auth()->user()->teacher->id);
        $class   = Grade::findOrFail($classid);
        // dd($teacher->id, $class->class_teacher, $teacherid, $class->class_teacher);
        // Security checks
      if ((int)$teacher->id !== (int)$class->class_teacher || (int)$teacherid !== (int)$class->class_teacher) {
            Log::warning('Unauthorized attendance submission attempt', [
                'teacher_id' => $teacher->id,
                'class_teacher' => $class->class_teacher,
            ]);
            return redirect()->route('teacher.attendance.list')
                             ->with('error', 'Unauthorized access — you are not the class teacher of this class.');
        }

        // Check if already taken
        $dataexist = Attendance::whereDate('attendence_date', $attenddate)
                                ->where('class_id', $classid)
                                ->exists();

        if ($dataexist) {
            Log::info('Attendance already exists for class', ['class_id' => $classid, 'date' => $attenddate]);
            return redirect()->route('teacher.attendance.list')
                             ->with('error', 'Attendance already taken!');
        }

        $request->validate([
            'class_id'      => 'required|numeric',
            'teacher_id'    => 'required|numeric',
            'attendences'   => 'required|array'
        ]);

        // Save attendance
        foreach ($request->attendences as $studentid => $attendence) {
            Attendance::create([
                'class_id'          => $classid,
                'teacher_id'        => $teacherid,
                'student_id'        => $studentid,
                'attendence_date'   => $attenddate,
                'attendence_status' => $attendence === 'present'
            ]);
        }

        Log::info('Attendance successfully stored', [
            'class_id' => $classid,
            'teacher_id' => $teacherid,
            'total_students' => count($request->attendences)
        ]);

        return redirect()->route('teacher.attendance.list')->with('success', 'Attendance submitted successfully!!');

    } catch (Exception $e) {
        Log::error('Error while storing attendance', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error', 'An unexpected error occurred while saving attendance.');
    }
}

  
    public function show(Attendance $attendance)
    {
        $attendances = Attendance::where('student_id',$attendance->id)->get();

        return view('backend.attendance.show', compact('attendances'));
    }

 
    public function edit(Attendance $attendance)
    {
        //
    }

 
    public function update(Request $request, Attendance $attendance)
    {
        //
    }


    public function destroy(Attendance $attendance)
    {
        //
    }

    
   public function attendanceListForTeacher()
{
    $teacherId = auth()->user()->teacher->id;
    $classes = Grade::where('class_teacher', $teacherId)->pluck('id');

    $attendances = Attendance::with([
            'class:id,class_name',
            'student.user:id,name',
            'teacher.user:id,name'
        ])
        ->whereIn('class_id', $classes)
        ->orderBy('attendence_date', 'desc')
        ->get();
    return view('backend.attendance.attendance-list', compact('attendances'));
}

}
