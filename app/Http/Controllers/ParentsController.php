<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Parents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentsController extends Controller
{
    public function index()
    {
        $parents = Parents::with(['user','children'])->latest()->paginate(10);
        
        return view('backend.parents.index', compact('parents'));
    }

    public function create()
    {
        return view('backend.parents.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'mother_name'       => 'required|string|max:255',
            'moccupation'       => 'required|string|max:255',
            'mqualification'    => 'required|string|max:255',
            'fqualification'    => 'required|string|max:255',
            'foccupation'       => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8',
            'phone'             => 'required|string|max:255',
            'current_address'   => 'required|string|max:255',
            
        ]);

        $user = User::create([
            'name'      => $request->name ." ". $request->mother_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'profile_picture' => 'avatar.png'
        ]);
        
        $user->parent()->create([
            'mother_name'       => $request->mother_name,
            'moccupation'       => $request->moccupation,
            'mqualification'       => $request->mqualification,
            'foccupation'       => $request->foccupation,
            'fqualification'       => $request->fqualification,
            'mother_name'       => $request->mother_name,
            'phone'             => $request->phone,
            'current_address'   => $request->current_address,
        ]);

        $user->assignRole('Parent');

        return redirect()->route('parents.index');
    }

   
    public function show(Parents $parents)
    {
        //
    }


    public function edit($id)
    {
        $parent = Parents::with('user')->findOrFail($id); 

        return view('backend.parents.edit', compact('parent'));
    }

    
    public function update(Request $request, $id)
    {
        $parents = Parents::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users,email,'.$parents->user_id,
            'gender'            => 'required|string',
            'phone'             => 'required|string|max:255',
            'current_address'   => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255'
        ]);

        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($parents->user->name).'-'.$parents->user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = $parents->user->profile_picture;
        }

        $parents->user()->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'profile_picture'   => $profile
        ]);

        $parents->update([
            'gender'            => $request->gender,
            'phone'             => $request->phone,
            'current_address'   => $request->current_address,
            'permanent_address' => $request->permanent_address
        ]);

        return redirect()->route('parents.index');
    }

    
    public function destroy($id)
    {
        $parent = Parents::findOrFail($id);

        $user = User::findOrFail($parent->user_id);
        $user->removeRole('Parent');

        if ($user->delete()) {
            if($user->profile_picture != 'avatar.png') {
                $image_path = public_path() . '/images/profile/' . $user->profile_picture;
                if (is_file($image_path) && file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        $parent->delete();

        return back();
    }
}
