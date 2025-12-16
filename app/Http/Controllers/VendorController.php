<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{

    public function index()
    {
        $vendors = Vendor::paginate(10);
        return view('backend.vendor.index',compact('vendors'));
    }


   
    public function store(Request $request)
    {
        $request->validate([
            'vendor_name' =>'required',
            'mobile' => 'required',
            'address' => 'nullable',
        ]);

        Vendor::create([
            'vendor_name' => $request->vendor_name,
            'mobile' => $request->mobile,
            'address' => $request->address,
        ]);
        return redirect()->route('inventory.create')->with('success','Vendor added successfully !!');
    }

  
    public function show(string $id)
    {
        //
    }

 
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success','Vendor deleted Successfully!!');
    }
}
