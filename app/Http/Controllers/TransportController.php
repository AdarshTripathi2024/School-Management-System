<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Transport;
class TransportController extends Controller
{
 
    public function index()
    {
        $transports =Transport::with('driver')->paginate('10');
        return view('backend.transport.index',compact('transports'));
    }

    public function create()
    {
        $drivers =Driver::all();
        return view('backend.transport.create',compact('drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number'=> 'required',
            'vehicle_type'=> 'required',
            'driver_id'=> 'required',
            'route'=> 'required',
        ]);
        Transport::create($validated);
        return redirect()->route('transport.index')->with('success','Transport Created Successfully !!');
    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        //
        $drivers = Driver::all();
        $transport = Transport::findOrFail($id);
        return view('backend.transport.edit',compact('drivers','transport'));
    }

    public function update(Request $request, string $id)
    {
        $transport = Transport::findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'vehicle_number'=> 'required',
            'vehicle_type'=> 'required',
            'driver_id'=> 'required',
            'route'=> 'required',
        ]);
        $transport->update($validated);
        return redirect()
            ->route('transport.index')
            ->with('success', 'Transport record updated successfully!');
    }

  
    public function destroy(string $id)
    {
        $transport = Transport::findOrFail($id);
        
        $transport->delete();

        return redirect()
            ->route('transport.index')
            ->with('success', 'Transport record deleted successfully!');
    }
    
}

