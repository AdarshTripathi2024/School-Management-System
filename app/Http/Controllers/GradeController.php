<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\GradeSubjectTeacher;
use App\Models\GradeSubjectTeacherHistory;
use App\Models\ClassTeacherHistory;
use Illuminate\Support\Facades\DB;


class GradeController extends Controller
{
    public function index()
    {
        $classes = Grade::latest()->paginate(10);
        return view('backend.classes.index',compact('classes'));
    }

  

    public function create()
    {
        return view('backend.classes.create');
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'class_name'        => 'required|string|max:255|unique:grades',
            'class_numeric'     => 'nullable|numeric',
            'class_description' => 'nullable|string|max:255'
        ]);

        try {
                Grade::create([
                    'class_name'        => $request->class_name,
                    'class_numeric'     => $request->class_numeric,
                    'class_description' => $request->class_description
                ]);

                return redirect()->route('classes.index')
                    ->with('success', 'Class created successfully!');
            } catch (\Exception $e) {
                return redirect()->route('classes.index')
                    ->with('error', 'Something went wrong while saving the class.');
            }
                
    }

  
    public function show(Grade $grade)
    {
        //
    }

  
    public function edit($id)
    {
        $teachers = Teacher::latest()->get();
        $class = Grade::findOrFail($id);

        return view('backend.classes.edit', compact('class','teachers'));
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name'        => 'required|string|max:255|unique:grades,class_name,'.$id,
            'class_numeric'     => 'required|numeric',
            'class_description' => 'nullable|string|max:255'
        ]);

        $class = Grade::findOrFail($id);

        $class->update([
            'class_name'        => $request->class_name,
            'class_numeric'     => $request->class_numeric,
            'class_description' => $request->class_description
        ]);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy($id)
    {
        $class = Grade::findOrFail($id);
        
        $class->subjects()->detach();
        $class->delete();

        return back();
    }

    public function viewDetail($id)
    {
        $detail = Grade::findOrFail($id);
        $assignedSubjectIds = GradeSubjectTeacher::where('grade_id', $id)
        ->pluck('subject_id')
        ->toArray();
        $allSubjects = Subject::whereNotIn('id', $assignedSubjectIds)->get();
        $allTeachers = Teacher::whereHas('user', function ($query) {
                            $query->where('status', 1);
                        })->with('user') // eager load user relation
                        ->get();
        $subjects = GradeSubjectTeacher::with([
                    'subject','grade', 'teacher.user'])
                    ->where('grade_id', $id)->get();
        // $subjects = DB::table('grade_subject_teacher as gst')
        //     ->leftJoin('subjects as s', 'gst.subject_id', '=', 's.id')
        //     ->leftJoin('grades as c', 'gst.grade_id', '=', 'c.id')
        //     ->leftJoin('teachers as t', 'gst.teacher_id', '=', 't.id')
        //     ->leftJoin('users as u', 't.user_id', '=', 'u.id')
        //     ->where('gst.grade_id', $id)
        //     ->select('u.name','gst.id', 's.name', 's.subject_code', 'c.class_name')
        //     ->get();
            return view('backend.classes.class-detail',compact('detail','subjects','allSubjects','allTeachers'));
            }
      

    
    public function storeAssignedSubjectTeacher(Request $request)
    {
        $request->validate([
            'class_id'        => 'required|numeric',
            'teacher_id'     => 'required|numeric',
            'subject_id' => 'required|numeric'
        ]);
        DB::beginTransaction();
        try {
                GradeSubjectTeacher::create([
                    'grade_id'        => $request->class_id,
                    'teacher_id'     => $request->teacher_id,
                    'subject_id' => $request->subject_id
                ]);
                GradeSubjectTeacherHistory::create([
                    'grade_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'teacher_id' => $request->teacher_id,
                    'from_date' => now(),
                    'to_date' => null,
                ]);
                DB::commit();

              return redirect()->route('class.view.detail', ['id' => $request->class_id])
    ->with('success', 'Subject and teacher assigned successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
               return redirect()->route('class.view.detail', ['id' => $request->class_id])
    ->with('error', 'Something went wrong !');
                
    }

    }

   public function storeClassTeacherToClass(Request $request)
{
    $request->validate([
        'class_id'   => 'required|numeric',
        'teacher_id' => 'required|numeric',
    ]);

    try {
       
        DB::beginTransaction();

        $class = Grade::findOrFail($request->class_id);

        //Get the class if the assigning teacher is the class teacher of any...
        $alreadyAssignedClass = Grade::where('class_teacher', $request->teacher_id)
                                    ->where('id', '!=', $class->id)
                                    ->first();

        // if the assigning teacher is class-teacher of any class already throw the error 
        if ($alreadyAssignedClass) {
            return redirect()
                ->route('class.view.detail', ['id' => $request->class_id])
                ->with('error', "This teacher is already assigned as class teacher for class '{$alreadyAssignedClass->class_name}'.");
        }

        
        //make the tenure of previous class teacher off in class teacher history table 
        if (!is_null($class->class_teacher)) {
            ClassTeacherHistory::where('grade_id', $class->id)
                ->whereNull('to_date')
                ->update(['to_date' => now()]);
        }

        // enter the new class teacher id into the grade table 
        $class->update([
            'class_teacher' => $request->teacher_id,
        ]);

       // also enter a new row for the new class teacher tenure and left the to_date NULL 
        ClassTeacherHistory::create([
            'grade_id'   => $class->id,
            'teacher_id' => $request->teacher_id,
            'from_date'  => now(),
            'to_date'    => null,
        ]);

        DB::commit(); 

        return redirect()
            ->route('class.view.detail', ['id' => $request->class_id])
            ->with('success', 'Class teacher assigned successfully!');
    } catch (\Exception $e) {
        DB::rollBack(); 

        return redirect()
            ->route('class.view.detail', ['id' => $request->class_id])
            ->with('error', 'Something went wrong while assigning class teacher: ' . $e->getMessage());
    }
}

public function removeSubjectFromClass(Request $request, $class_id, $subject_id)
{
try {
        // Find the specific record in the pivot table
        $record = GradeSubjectTeacher::where('grade_id', $class_id)
                    ->where('subject_id', $subject_id)
                    ->first();

        if ($record) {
            // Update the corresponding history entry (close the record)
            GradeSubjectTeacherHistory::where('grade_id', $class_id)
                ->where('subject_id', $subject_id)
                ->where('teacher_id', $record->teacher_id)
                ->whereNull('to_date')
                ->update(['to_date' => now()]);

            // Delete the record from the current assignment table
            $record->delete();

            return back()->with('success', 'Subject removed and history updated successfully.');
        }else {
            return back()->with('error', 'No matching subject found for this class.');
        }

    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong while removing the subject.'. $e);
    }
}

public function changeSubjectTeacherOfClass(Request $request)
{
      $request->validate([
            'class_id'        => 'required|numeric',
            'teacher_id'        => 'required|numeric',
            'grade_subject_teacher_id' => 'required|numeric'
        ]);
        DB::beginTransaction();
        try {
                $gst_id = $request->get('grade_subject_teacher_id');
                $gst_record = GradeSubjectTeacher::findOrFail($gst_id);
                GradeSubjectTeacherHistory::where('grade_id', $gst_record->grade_id)
                ->where('subject_id', $gst_record->subject_id)
                ->where('teacher_id', $gst_record->teacher_id)
                ->whereNull('to_date')
                ->update(['to_date' => now()]);


                $gst_record->update([
                    'teacher_id' => $request->teacher_id,
                ]);

                GradeSubjectTeacherHistory::create([
                    'grade_id' => $gst_record->grade_id,
                    'subject_id' => $gst_record->subject_id,
                    'teacher_id' => $request->teacher_id,
                    'from_date' => now(),
                    'to_date' => null,
                ]);
                DB::commit();

              return redirect()->route('class.view.detail', ['id' => $request->class_id])
    ->with('success', 'Teacher assigned successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
               return redirect()->route('class.view.detail', ['id' => $request->class_id])
    ->with('error', 'Something went wrong !');
                
    }
}

}