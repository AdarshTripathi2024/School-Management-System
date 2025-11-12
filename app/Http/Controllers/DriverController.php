<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $drivers = Driver::latest()->paginate(10);
        return view('backend.driver.index',compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('backend.driver.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'date_of_joining' => 'required',
            'license_number' => 'required',
        ]);

        Driver::create([
            'name' => $request->name,
            'license_number' => $request->license_number,
            'date_of_joining' => $request->date_of_joining,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address
        ]);
        return redirect()->route('driver.index')->with('success','Driver created successfully !!');

    }

    public function show(string $id)
    {
        //
    }

    
    public function edit(string $id)
    {
        $driver = Driver::findOrFail($id);
        return view('backend.driver.edit',compact('driver'));
    }

 
    public function update(Request $request, string $id)
    {
        $driver = Driver::findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'license_number'  => 'required|string|max:50',
            'phone'           => 'required|string|max:15',
            'email'           => 'required|email|max:255',
            'address'         => 'required|string',
            'date_of_joining' => 'required',
        ]);

        // Update the driver record
        $driver->update($validated);

        return redirect()
            ->route('driver.index')
            ->with('success', 'Driver record updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
