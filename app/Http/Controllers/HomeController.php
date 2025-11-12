<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parents;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\GradeSubjectTeacher;
use App\Models\User;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    //
       public function __construct()
    {
        $this->middleware('auth');
    }

  
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            $parents  = Parents::latest()->get();
            $teachers = Teacher::latest()->get();
            $students = Student::latest()->get();
            $subjects = Subject::latest()->get();
            $classes  = Grade::latest()->get();

            return view('home', compact('parents', 'teachers', 'students', 'subjects', 'classes'));

            } elseif ($user->hasRole('Teacher')) {
                $teacherId = auth()->user()->teacher->id;
                $teacher = Teacher::with(['user', 'subjects', 'grades'])
                                ->findOrFail($teacherId);
                $students =  Student::latest()->get();
                $assignments = GradeSubjectTeacher::where('teacher_id', $teacherId)->get();
                $subjectsCount = $assignments->pluck('subject_id')->unique()->count();
                $gradesCount = $assignments->pluck('grade_id')->unique()->count();
                $teaching = GradeSubjectTeacher::with(['grade', 'subject'])
                        ->where('teacher_id', $teacherId)
                        ->get()
                        ->groupBy(fn($item) => $item->grade->class_name);
                $classes = $teacher->classTeacherOf;
                // dd($classes);
                $today = now();
                $notices = Notice::whereDate('notice_date', '<=', $today)
                    ->whereDate('expiry_date', '>=', $today)
                    ->orderBy('notice_date', 'desc')
                    ->take(10)
                    ->get();
                  
                return view('home', compact('teacher', 'students', 'teaching', 'subjectsCount', 'gradesCount','notices','classes'));

            } 
            elseif ($user->hasRole('Parent')) {
                    $parents = Parents::with(['children'])
                              ->withCount('children')
                              ->findOrFail($user->parent->id);
                    $today = now();
                   $notices = Notice::whereDate('expiry_date', '>=', now())
                            ->orderBy('notice_date', 'asc')
                            ->take(10)
                            ->get();

                        //   dd(Notice::all());
                    return view('home', compact('parents','notices'));

            } elseif ($user->hasRole('Student')) {
            $student = Student::with(['user', 'parent', 'class', 'attendances'])
                              ->findOrFail($user->student->id);
            $today = now();
            $notices = Notice::whereDate('notice_date', '<=', $today)
                ->whereDate('expiry_date', '>=', $today)
                ->orderBy('notice_date', 'desc')
                ->take(10)
                ->get();
            return view('home', compact('student','notices'));

        } else {
            return 'NO ROLE ASSIGNED YET!';
        }
    }

       public function profile() 
    {
        return view('profile.index');
    }

    public function profileEdit(Request $request)
    {
         $profile = auth()->user();
        return view('profile.edit', compact('profile'));
    }

    public function profileUpdate(Request $request)
    {
        $profile = User::findOrFail(auth()->user()->id);
        $request->validate([
            'name'        => 'required',
            'email'     => 'required|email|unique:users,email,'.auth()->user()->id,
          
        ]);
         if ($request->hasFile('profile_picture')) {
            $photo = Str::slug($profile->name).'-'.$profile->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $photo);
        } else {
            $photo = $profile->profile_picture;
        }
        $profile->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'profile_picture'   => $photo
        ]);
        return redirect()->route('profile')->with('success','Profile updated Successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'=> 'required',
            'new_password'=> 'required|min:5|confirmed',
        ]);
        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }
        $user->update([
            'password'=> Hash::make($request->new_password),
        ]);
        auth()->logout();
        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in again.');
    }

}
