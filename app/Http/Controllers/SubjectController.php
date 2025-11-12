<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    //
     public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('backend.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:subjects',
            'subject_code'  => 'required|numeric',
            'description' => 'nullable|string|max:255',

        ]);

        Subject::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'subject_code'  => $request->subject_code,
            'description'   => $request->description
        ]);

        return redirect()->route('subject.index')->with('success', 'Subject created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $teachers = Teacher::latest()->get();

        return view('backend.subjects.edit', compact('subject','teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:subjects,name,'.$subject->id,
            'subject_code'  => 'required|numeric',
           'description' => 'nullable|string|max:255',

        ]);

        $subject->update([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'subject_code'  => $request->subject_code,
            'description'   => $request->description
        ]);

        return redirect()->route('subject.index')->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return back();
    }
}
