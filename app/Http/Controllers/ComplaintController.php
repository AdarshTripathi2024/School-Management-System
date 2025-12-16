<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Parents;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    public function index()
    {
        $user = auth()->user();

    // For Admins — show all complaints
    if ($user->hasRole('Admin')) {
        $complaints = Complaint::orderBy('updated_at', 'desc')->paginate(10);
    } 
    // For Parents — show complaints related to their children
    elseif ($user->hasRole('Parent')) {
        $parent = $user->parent; // get parent model
        $studentIds = $parent ? $parent->children()->pluck('id')->toArray() : [];

        $complaints = Complaint::whereIn('student_id', $studentIds)
            ->orWhere('complaint_from_id', $user->id)
            ->orWhere('complaint_to_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    } 
    // For Students / Others — show complaints involving them
    else {
        $complaints = Complaint::where('complaint_from_id', $user->id)
            ->orWhere('complaint_to_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
        return view('backend.complaint.index',compact('complaints'));
    }

    public function create()
    {
        $toUser = null;
        $students = null;
       if(auth()->user()->hasRole('Admin')){
             $toUser = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Student');
        })->where('id', '!=', auth()->id())
        ->get();
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'Student');
        })->where('id', '!=', auth()->id())
        ->get();
       }

      if (auth()->user()->hasRole('Parent')) {
    $parent_id = auth()->user()->parent->id;

    $children = Student::where('parent_id', $parent_id)->get();

    $class_teacher_ids = Grade::whereIn('id', $children->pluck('class_id'))
                              ->pluck('class_teacher');

    $user_ids = Teacher::whereIn('id', $class_teacher_ids)
                       ->pluck('user_id');
    $toUser = User::whereIn('id', $user_ids)->get();
    $admins = User::role('Admin')->get();
    $toUser = $toUser->merge($admins);

    $students = User::whereIn('id',$children->pluck('user_id'))->get();

}
if(auth()->user()->hasRole('Teacher')){
    $teacher_id = auth()->user()->teacher->id;
    $class= Grade::where('class_teacher',$teacher_id)->get();
    $parent_id = Student::whereIn('class_id',$class->pluck('id'))->pluck('parent_id');
    $user_ids= Parents::whereIn('id',$parent_id)->pluck('user_id');
    $toUser = User::whereIn('id',$user_ids)->get();
    $admins = User::role('Admin')->get();
    $toUser = $toUser->merge($admins);

    $student_ids = Student::whereIn('class_id', $class->pluck('id'))->pluck('user_id');
    $students = User::whereIn('id',$student_ids)->get();
    // dd($students);
}

       //dd($toUser);
        return view('backend.complaint.create',compact('toUser','students'));
        
    }

  
public function store(Request $request)
{
    try {

        $request->validate([
            'complaint_to_id' => 'required',
            'student_id' => 'nullable',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('complaints', 'public');
            Log::info('Attachment uploaded', ['file_path' => $filePath]);
        }

        $complaint = Complaint::create([
            'complaint_to_id' => $request->complaint_to_id,
            'complaint_from_id' => auth()->user()->id,
            'student_id' => $request->student_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment' => $filePath,
        ]);
        DB::table('complaint_logs')->insert([
            'complaint_id' => $complaint->id,
            'status' => 'pending',
            'remark' => 'New Complaint Created',
            'changed_by' => $complaint->complaint_from_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('complaint.index')
            ->with('success', 'Complaint added successfully!');
    } catch (\Throwable $e) {
        // Log full error details
        Log::error('Error while storing complaint', [
            'error_message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()
            ->withErrors(['error' => 'Something went wrong while submitting your complaint.'])
            ->withInput();
    }
}

    public function edit(string $id)
    {
        $complaint = Complaint::findOrFail($id);
          $toUser = null;
        $students = null;
        if($complaint->complaint_from_id != auth()->user()->id){
            return redirect()->route('complaint.index')->with('success','Unauthorised Activity Sensed - Not Allowed to edit others complaint');
        }
        if(!$complaint){
            return redirect()->route('complaint.index')->with('error','no Complaint found to edit');
        }
       if(auth()->user()->hasRole('Admin')){
             $toUser = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Student');
        })->where('id', '!=', auth()->id())
        ->get();
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'Student');
        })->where('id', '!=', auth()->id())
        ->get();
       }

      if (auth()->user()->hasRole('Parent')) {
    $parent_id = auth()->user()->parent->id;

    $children = Student::where('parent_id', $parent_id)->get();

    $class_teacher_ids = Grade::whereIn('id', $children->pluck('class_id'))
                              ->pluck('class_teacher');

    $user_ids = Teacher::whereIn('id', $class_teacher_ids)
                       ->pluck('user_id');
    $toUser = User::whereIn('id', $user_ids)->get();
    $admins = User::role('Admin')->get();
    $toUser = $toUser->merge($admins);

    $students = User::whereIn('id',$children->pluck('user_id'))->get();
   
}
if(auth()->user()->hasRole('Teacher')){
     $teacher_id = auth()->user()->teacher->id;
            $class= Grade::where('class_teacher',$teacher_id)->get();
            $parent_id = Student::whereIn('class_id',$class->pluck('id'))->pluck('parent_id');
            $user_ids= Parents::whereIn('id',$parent_id)->pluck('user_id');
            $toUser = User::whereIn('id',$user_ids)->get();
            $admins = User::role('Admin')->get();
            $toUser = $toUser->merge($admins);

            $student_ids = Student::whereIn('class_id', $class->pluck('id'))->pluck('user_id');
            $students = User::whereIn('id',$student_ids)->get();
            // dd($students);
       }
        return view('backend.complaint.edit',compact('complaint','toUser','students'));
    }

    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);
        if($complaint->complaint_from_id != auth()->user()->id){
            return redirect()->route('complaint.index')->with('error','Unauthorised Activity Sensed - Not Allowed to edit others complaint');
        }
        $request->validate([
            'student_id' => 'nullable',
            'complaint_to_id' => 'nullable',
            'subject' => 'required',
            'description' => 'required',
            'solution' => 'required',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'student_id' => $request->get('student_id'),
            'subject' => $request->get('subject'),
            'complaint_to_id' => $request->get('student_id'),
            'complaint_from_id' => auth()->user()->id,
            'student_id' => $request->get('student_id'),
            'description' => $request->get('description'),
        ];
        
    if ($request->hasFile('attachment')) {
        if ($complaint->attachment && Storage::disk('public')->exists($complaint->attachment)) {
            Storage::disk('public')->delete($complaint->attachment);
        }
        $file = $request->file('attachment');
        $filename = Str::slug($request->subject) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('complaints', $filename, 'public');
        $data['attachment'] = $path;
    }

    $complaint->update($data);
    return redirect()->route('complaint.index')->with('success','Complaint updated successfully');
    }

    public function destroy(String $id)
    {
         $complaint = Complaint::findOrFail($id);
         if($complaint->complaint_from_id != auth()->user()->id){
            return redirect()->route('complaint.index')->with('error','Unauthorised Activity Sensed - Not Allowed to delete others complaint');
        }
        if(!$complaint){
            return redirect()->route('complaint.index')->with('error','No Complaint found to delete');
        }else{
            $complaint::delete();
            return redirect()->route('complaint.index')->with('error','Complaint record deleted successfully!!');
        }
    }

    public function show(String $id)
    {
        $user = auth()->user();
        $complaint = Complaint::with(['fromUser', 'toUser', 'student'])->findOrFail($id);
        if(!$complaint){
            return redirect()->route('complaint.index')->with('error','No complaint Data found');
        }
        if($complaint->complaint_to_id == $user->id || $user->hasRole('Admin') || $complaint->complaint_from_id == $user->id ){
            $complaint_log = DB::table('complaint_logs')->join('users','complaint_logs.changed_by','=','users.id')->where('complaint_logs.complaint_id',$id)
            ->select('complaint_logs.*', 'users.name as changed_by_name')->orderBy('complaint_logs.created_at','DESC')->get();
            return view('backend.complaint.show',compact('complaint','complaint_log'));
        }else{
            return redirect()->route('compliant.index')->with('error','You are not authorised to see the complaint');
        }
    }

    public function changeStatus(Request $request, String $id)
    {
       $request->validate([
        'status' => 'required|string',
        'remark' => 'required|string|max:500',
    ]);

    $user = auth()->user();
    $complaint = Complaint::findOrFail($id);
        if($complaint->complaint_to_id == $user->id || $user->hasRole('Admin') || $complaint->complaint_from_id == $user->id ){
              $complaint->update([
            'status' => $request->status,
        ]);
        DB::table('complaint_logs')->insert([
            'complaint_id' => $complaint->id,
            'changed_by' => $user->id,
            'status' => $request->status,
            'remark' => $request->remark,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaint.index')->with('success','Complaint status changed successfully !!');
        }
         return redirect()
        ->route('complaint.index')
        ->with('error', 'You are not authorized to change this complaint status.');
    }

}
