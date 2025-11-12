<?php

namespace App\Http\Controllers;

use App\Models\holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
  
    public function index()
    {
     $holidays = Holiday::where('date', '>=', Carbon::today())
    ->orderBy('date', 'asc')  // ascending so nearest holidays appear first
    ->paginate(10);
    $allHolidays = Holiday::all();
        return view('backend.holiday.index', compact('holidays','allHolidays'));
    }

    public function create()
    {
        //
        return view('backend.holiday.create');
    }

   
    public function store(Request $request)
    {
        //
        $request->validate([
            'h_date' => 'required|date|unique:holidays,date',
            'occasion' => 'required|string|max:255',
        ]);

        Holiday::create([
            'date'=> $request->h_date,
            'occasion'=> $request->occasion,
            'is_for_teacher' => $request->has('is_for_teacher') ? 1 : 0, 
            'remark'=> $request->remark,
        ]);

        return redirect()->route('holiday.index')->with('success', 'Holiday added successfully!');
    }

   
    public function show(string $id)
    {
        //
    }

    
    public function edit(string $id)
    {
        //
        $holiday = Holiday::findOrFail($id);
        return view('backend.holiday.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $holiday = Holiday::findOrFail($id);
         $request->validate([
            'h_date' => 'required|date|unique:holidays,date,'.$holiday->id,
            'occasion' => 'required|string|max:255',
        ]);

        if($holiday){
              $holiday->update([
                'date'=> $request->h_date,
                'occasion'=> $request->occasion,
                'is_for_teacher' => $request->has('is_for_teacher') ? 1 : 0, 
                'remark'=> $request->remark,
            ]);
        return redirect()->route('holiday.index')->with('success', 'Holiday updated successfully!');
        }else{
            return redirect()->route('holiday.index')->with('error', 'Something Went Wrong');
        }
        
    }

   
    public function destroy(string $id)
    {
        //
    }
}
