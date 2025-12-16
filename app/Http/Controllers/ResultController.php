<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\User;
use App\Models\StudentSubjectMark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ResultController extends Controller
{
   
    public function index()
    {
        $user = User::with('teacher')->find(auth()->id());
        $class_id = Grade::where('class_teacher', $user->teacher->id)->value('id');

       // Load ALL exams
    $exams = Exam::with([
        'results' => function ($q) use ($class_id) {
            $q->where('class_id', $class_id)
              ->with(['student.user', 'class', 'subjectMarks.subject']);
        }
    ])
    ->orderByDesc('status')
    ->orderBy('id')
    ->get();

    // Group only filtered results
    $resultsGrouped = $exams
        ->flatMap(fn($exam) => $exam->results)
        ->groupBy('exam_id');
        return view('backend.result.index',compact('exams','resultsGrouped'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $classes = $teacher->classTeacherOf;
        if (!$classes) {
            return redirect()->back()->with('error', 'No class assigned to you.');
        }
        $exam = Exam::where('status', 1)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'No active exam found.');
        }
        $classData = [];
        foreach ($classes as $class) {
        $students = $class->students()->with('user')->get();
        $subjects = $class->subjects()->get();
        foreach ($students as $student) {
            $existingResult = Result::where('exam_id', $exam->id)
                ->where('class_id', $class->id)
                ->where('student_id', $student->id)
                ->first();
            $student->result_uploaded = $existingResult ? true : false;
            $student->result_id = $existingResult ? $existingResult->id : null;
        }
        $classData[$class->id] = [
            'class' => $class,
            'students' => $students,
            'subjects' => $subjects,
        ];
        }
        return view('backend.result.create',compact('classes','classData','exam'));
    }

    
public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'grade_id'   => 'required|exists:grades,id',
        'exam_id'    => 'required|exists:exams,id',
        'theory_max' => 'required|array',
        'theory_obtained' => 'array',
        'practical_max' => 'array',
        'practical_obtained' => 'array',
    ]);

    $studentId = $request->student_id;
    $classId   = $request->grade_id;
    $examId    = $request->exam_id;

    try {
        // Prevent duplicate result
        if (Result::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->where('student_id', $studentId)
                ->exists()) {
            return back()->with('error', 'Result for this student in this exam already exists.');
        }

        // Begin transaction
        DB::beginTransaction();

        $totalMaxSum = 0;
        $totalObtainedSum = 0;

        // Create main result record
        $result = Result::create([
            'exam_id' => $examId,
            'class_id' => $classId,
            'student_id' => $studentId,
            'total' => 0,
            'grandtotal' => 0,
            'percentage' => 0,
        ]);

        // Create subject-wise marks
        foreach ($request->theory_max as $subjectId => $theoryMax) {
            $theoryObt = $request->theory_obtained[$subjectId] ?? 0;
            $pracMax   = $request->practical_max[$subjectId] ?? 0;
            $pracObt   = $request->practical_obtained[$subjectId] ?? 0;

            $totalMax  = $theoryMax + $pracMax;
            $totalObt  = $theoryObt + $pracObt;

            $totalMaxSum += $totalMax;
            $totalObtainedSum += $totalObt;

            DB::table('student_subject_marks')->insert([
                'result_id' => $result->id,
                'subject_id' => $subjectId,
                'theory_total' => $theoryMax,
                'obtained_theory' => $theoryObt,
                'practical_total' => $pracMax,
                'obtained_practical' => $pracObt,
                'total_marks' => $totalMax,
                'obtained_total' => $totalObt,
            ]);
        }

        // Calculate percentage and update main result
        $percentage = $totalMaxSum > 0 ? ($totalObtainedSum / $totalMaxSum) * 100 : 0;
        $result->update([
            'total' => $totalMaxSum,
            'grandtotal' => $totalObtainedSum,
            'percentage' => $percentage,
        ]);

        // Commit transaction
        DB::commit();

        return back()->with('success', 'Result uploaded successfully!');

    } catch (\Throwable $e) {
        // Rollback on error
        DB::rollBack();

        // Log the full error for debugging
        Log::error('Result Upload Failed', [
            'student_id' => $studentId,
            'exam_id' => $examId,
            'class_id' => $classId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->with('error', 'Something went wrong while saving the result. Please try again.');
    }
}

    public function show(string $id)
    {
        $result = Result::with(['student.user', 'class', 'exam', 'subjectMarks.subject'])
        ->findOrFail($id);
        return view('backend.result.show', compact('result'));
    }


    public function edit(string $id)
    {
        $result = Result::with(['student.user','class','exam','subjectMarks.subject'])->findOrFail($id);
        return view('backend.result.edit',compact('result'));
    }

 
    public function update(Request $request, string $id)
    {
        // dd($request->toArray());

         $request->validate([
        'theory_max' => 'required|array',
        'theory_obtained' => 'array',
        'practical_max' => 'array',
        'practical_obtained' => 'array',
    ]);
    try {
        DB::beginTransaction();
        $totalMaxSum = 0;
        $totalObtainedSum = 0;
        $result = Result::findOrFail($id);
        foreach ($request->theory_max as $subjectId => $theoryMax) {
            $theoryObt = $request->theory_obtained[$subjectId] ?? 0;
            $pracMax   = $request->practical_max[$subjectId] ?? 0;
            $pracObt   = $request->practical_obtained[$subjectId] ?? 0;
            $totalMax  = $theoryMax + $pracMax;
            $totalObt  = $theoryObt + $pracObt;
            $totalMaxSum += $totalMax;
            $totalObtainedSum += $totalObt;

            DB::table('student_subject_marks')->where('result_id',$id)->where('subject_id',$subjectId)->update([
                'theory_total' => $theoryMax,
                'obtained_theory' => $theoryObt,
                'practical_total' => $pracMax,
                'obtained_practical' => $pracObt,
                'total_marks' => $totalMax,
                'obtained_total' => $totalObt,
            ]);
        }
        $percentage = $totalMaxSum > 0 ? ($totalObtainedSum / $totalMaxSum) * 100 : 0;
        $result->update([
            'total' => $totalMaxSum,
            'grandtotal' => $totalObtainedSum,
            'percentage' => $percentage,
        ]);
        DB::commit();
        return redirect('')->with('success', 'Result uploaded successfully!');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Result Upload Failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->with('error', 'Something went wrong while saving the result. Please try again.');
    }
    }

    public function destroy(string $id)
    {
        //
    }

    public function downloadPdf($id)
    {
        $result = Result::with(['student','student.user', 'class', 'exam', 'subjectMarks.subject'])
                        ->findOrFail($id);
        $pdf = Pdf::loadView('backend.result.template', compact('result'))->setPaper('a4', 'portrait');
        return $pdf->download('result_' . Str::slug($result->student->user->name) . '.pdf');
    }

    public function examResult($id)
    {
        $classes= Grade::all();
        $exam_id = $id;
        return view('backend.result.exam-result',compact('classes','exam_id'));
    }

    public function classResult($exam_id, $class_id)
    {
       $results = Result::with(['student.user','class'])->where('exam_id',$exam_id)->where('class_id',$class_id)->paginate(10);
        return view('backend.result.class-result',compact('results','exam_id','class_id'));
    }

    public function studentResult($result_id)
    {
       $result = Result::with(['student.user','class','exam','subjectMarks'])->findOrFail($result_id);
        return view('backend.result.show',compact('result'));
    }

    public function parentIndex()
    {
        $user = User::with('parent.children')->find(auth()->id());
        $parent = $user->parent;
        $exams = Exam::all();
        $studentIds = $parent->children->pluck('id');
        $results = Result::with([
            'student.user', 'exam','class'
        ])->whereIn('student_id', $studentIds)
        ->get();
        $resultsGrouped = $results->groupBy('exam_id')->map(function ($group) {
    return $group->keyBy('student_id'); });  // <-- KEY BY STUDENT

        return view('backend.result.parent-index',compact('exams', 'resultsGrouped', 'user'));
    }


}
