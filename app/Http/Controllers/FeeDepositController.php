<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Student;

class FeeDepositController extends Controller
{

    public function index()
    {
       
        $deposits = Grade::all();
        return view('backend.feedeposit.index', compact('classes','deposits'));
    }

    public function create()
    {
        $classes = Grade::all();
        return view('backend.feedeposit.create',compact('classes'));
    }

  
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('backend.feedeposit.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


  public function searchStudent(Request $request)
{
    $query = Student::query()
        ->select('students.*', 'users.name')
        ->join('users', 'users.id', '=', 'students.user_id');

    if ($request->class_id) {
        $query->where('students.class_id', $request->class_id);
    }

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('users.name', 'like', '%' . $request->search . '%');
        });
    }

    return response()->json($query->get());
}


}
