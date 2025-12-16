<?php

namespace App\Http\Controllers;

use App\Models\FeeComponent;
use App\Models\FeeStructure;
use App\Models\Grade;
use App\Models\FeeStructureChild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeesController extends Controller
{

    public function index()
    {
        $components = FeeComponent::paginate(10);
        return view('backend.fees.index',compact('components'));
    }
    
    public function index_structure()
    {
        $structures = FeeStructure::with(['grade','children'])->paginate(10);
        return view('backend.fees.index-structure',compact('structures'));
    }

    public function create_fee_structure()
    {
        $class_ids = FeeStructure::all()->pluck('class_id');
        $classes = Grade::whereNotIn('id',$class_ids)->get();
        $components = FeeComponent::where('name', '!=', 'Transport Fee')->get();
        return view('backend.fees.create-structure',compact('classes','components'));
    }

    public function edit_fee_structure(string $id)
    {
        $fee_structure = FeeStructure::with(['children','grade'])->findOrFail($id);
        $components = FeeComponent::where('name', '!=', 'Transport Fee')->get();
        return view('backend.fees.edit-structure',compact('fee_structure','components'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        FeeComponent::create([
            'name' => $request->name,
        ]);
        return redirect()->route('fees.index')->with('success','Fee Component Added successfully!');
    }

    public function store_fee_structure(Request $request)
    {
        $request->validate([
            'amount' => 'required|array',
            'amount.*' => 'required|numeric',
            'component' => 'required|array',
            'component.*' => 'required|numeric',
            'monthly' => 'required',
            'quarterly' => 'required',
            'halfyearly' => 'required',
            'annual' => 'required',
            'total' => 'required',
            'class' => 'required'
        ]);
        DB::beginTransaction();
        try{
            $fee_structure = FeeStructure::create([
                'class_id' => $request->class,
                'total_fee' => $request->total,
                'monthly_installment' => $request->monthly,
                'quarterly_installment' => $request->quarterly,
                'halfyearly_installment' => $request->halfyearly,
                'academic_year' => date('Y'),
            ]);
        
            foreach($request->component as $i => $comp)
            {
                if($request->amount[$i] == 0){
                        continue;
                }
                $amount = $request->amount[$i];
                FeeStructureChild::create([
                        'parent_id' => $fee_structure->id,
                        'fee_component_id' => $comp,
                        'amount' =>$amount,
                ]);
            }
            DB::commit();
            return redirect()->route('fees.structure.index')->with('success','Fee Component Added successfully!');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            \Log::error('Fee Structure Error',[
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
        return back()->with('error', 'Error Adding Fee Structure: ' . $e->getMessage())->withInput();

        }
    }


    public function destroy(string $id)
    {
        FeeComponent::findOrFail($id)->delete();
        return redirect()->route('fees.index')->with('success','Component deleted successfully!');
    }
}
