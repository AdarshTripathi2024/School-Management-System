<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Grade;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Transport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class StudentController extends Controller
{
       public function index()
    {
       
        $students = Student::with('class')->latest()->paginate(10);
        return view('backend.students.index', compact('students'));
    }
    
    public function create()
    {
        $transports = Transport::all();
        $classes = Grade::latest()->get();
        $parents = Parents::with('user')->latest()->get();
        
        return view('backend.students.create', compact('classes','parents', 'transports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8',
            'parent_id'         => 'required|numeric',
            'class_id'          => 'required|numeric',
            'roll_number'       => [
                'required',
                'numeric',
                Rule::unique('students')->where(function ($query) use ($request) {
                    return $query->where('class_id', $request->class_id);
                })
            ],
            'gender'            => 'required|string',
            'phone'             => 'required|string|max:255',
            'dateofbirth'       => 'required|date',
            'current_address'   => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'require_transport' => 'nullable|boolean',
            'transport_id'      =>  'nullable|exists:transports,id',

        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password)
        ]);

        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($user->name).'-'.$user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = 'avatar.png';
        }
        $user->update([
            'profile_picture' => $profile
        ]);

       $student =  $user->student()->create([
            'parent_id'         => $request->parent_id,
            'class_id'          => $request->class_id,
            'roll_number'       => $request->roll_number,
            'gender'            => $request->gender,
            'phone'             => $request->phone,
            'dateofbirth'       => $request->dateofbirth,
            'current_address'   => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'require_transport' => $request->has('require_transport'),
        ]);

        $user->assignRole('Student');

        if ($request->has('requires_transport') && $request->filled('transport_id')) 
            {
                $exists = DB::table('student_transport')
                    ->where('student_id', $student->id)
                    ->where('transport_id', $request->transport_id)
                    ->exists();

                if (! $exists) {
                    DB::table('student_transport')->insert([
                        'student_id'   => $student->id,
                        'transport_id' => $request->transport_id,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }
        return redirect()->route('student.index');
    }


    public function show(Student $student)
    {
        $class = Grade::with('subjects')->where('id', $student->class_id)->first();
        
        return view('backend.students.show', compact('class','student'));
    }

  
    public function edit(Student $student)
    {
        $classes = Grade::latest()->get();
        $parents = Parents::with('user')->latest()->get();
        return view('backend.students.edit', compact('classes','parents','student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users,email,'.$student->user_id,
            'parent_id'         => 'required|numeric',
            'class_id'          => 'required|numeric',
            'roll_number'       => [
                'required',
                'numeric',
                Rule::unique('students')->ignore($student->id)->where(function ($query) use ($request) {
                    return $query->where('class_id', $request->class_id);
                })
            ],
            'gender'            => 'required|string',
            'phone'             => 'required|string|max:255',
            'dateofbirth'       => 'required|date',
            'current_address'   => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'require_transport' => 'nullable|boolean',
            'transport_id'      => 'nullable|exists:transports,id',
        ]);

        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($student->user->name).'-'.$student->user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = $student->user->profile_picture;
        }

        $student->user()->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'profile_picture'   => $profile
        ]);

        $student->update([
            'parent_id'         => $request->parent_id,
            'class_id'          => $request->class_id,
            'roll_number'       => $request->roll_number,
            'gender'            => $request->gender,
            'phone'             => $request->phone,
            'dateofbirth'       => $request->dateofbirth,
            'current_address'   => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'require_transport' => $request->has('require_transport'),
            
        ]);

        if($request->has('require_transport') && $this->request->filled('transport_id'))
        {
            $existing = DB::table('student_transport')
                            ->where('student_id',$student->id)
                            ->first();
            if($existing){
                DB::table('student_transport')
                ->where('student_id',$student->id)
                ->update([
                    'transport_id' =>$request->transport_id,
                    'updated_at' =>now(),
                ]);
            }else{
                DB::table('student_transport')->insert([
                    'student_id' => $student_id,
                    'transport_id' => $request->transport_id,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);
            }
        }else{
            DB::table('student_transport')->where('student_id',$student->id)->delete();
        }

        return redirect()->route('student.index')->with('success','Student data updated successfully!!');
    }

    public function destroy(Student $student)
    {
        $user = User::findOrFail($student->user_id);
        $user->student()->delete();
        $user->removeRole('Student');

        if ($user->delete()) {
            if($user->profile_picture != 'avatar.png') {
                $image_path = public_path() . '/images/profile/' . $user->profile_picture;
                if (is_file($image_path) && file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        return back();
    }
}
