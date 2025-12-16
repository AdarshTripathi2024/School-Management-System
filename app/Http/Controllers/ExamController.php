<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExamController extends Controller
{

    public function index()
    {
        $exams = Exam::paginate('10');
        return view('backend.exam.index', compact('exams'));
    }

    public function create()
    {
        //
        return view('backend.exam.create');
    }

  
    public function store(Request $request)
    {
        //
        $request->validate([
            'exam_name'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
            'description'=> 'nullable',
            'time_table'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', 
        ]);
        // Handle file upload (if any)
        $filePath = null;
        if ($request->hasFile('time_table')) {
            $filePath = $request->file('time_table')->store('timetable', 'public');
        }

        Exam::create([
            'exam_name'=> $request->exam_name,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date,
            'description'=> $request->description,
            'timetable'=> $filePath,

        ]);

        return redirect()->route('exam.index')->with('success','Exam record saved successfully !!');
        
    }

   
    public function show(string $id)
    {
        //
    }

   
    public function edit(string $id)
    {
       $exam = Exam::findOrFail($id);
       return view('backend.exam.edit', compact('exam'));
    }

   
    public function update(Request $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        $request->validate([
            'exam_name'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
            'description'=> 'nullable',
            'time_table'=> 'nullable',
            'status' => 'required|in:0,1',
            'time_table' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $data = [
            'exam_name'=> $request->exam_name,
            'start_date'=> $request->start_date,
            'end_date'=> $request->end_date,
            'description'=> $request->description,
            'status' => $request->status,
        ];

        // Handle attachment replacement
    if ($request->hasFile('time_table')) {
        // Delete old file if exists
        if ($exam->timetable && Storage::disk('public')->exists($exam->timetable)) {
            Storage::disk('public')->delete($exam->time_table);
        }

        // Create a unique, descriptive name for the new file
        $file = $request->file('time_table');
        $filename = Str::slug($request->exam_name) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store file under 'notices' directory
        $path = $file->storeAs('timetable', $filename, 'public');

        // Save the new path
        $data['timetable'] = $path;
    }


        $exam->update($data);
        return redirect()->route('exam.index')->with('Exam Record Updated Successfully !!');
    }

    public function destroy(string $id)
    {
        //
    
    }

}
