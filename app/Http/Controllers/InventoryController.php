<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use App\Models\AddInventory;
use App\Models\AddInventoryChild;
use App\Models\InventoryHistory;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    public function index()
    {
        $purchase = AddInventory::with(['vendor','inv_added_by'])->paginate(10);
        return view('backend.inventory.index', compact('purchase'));
    }

    public function create()
    {
        $vendors= Vendor::orderBy('id','desc')->get();
        $items= Item::orderBy('id','desc')->get();
        $inv_count = AddInventory::count() + 1;
        $year = date('Y');
        $invoice_no = 'INV'.$year . str_pad($inv_count, 4, '0', STR_PAD_LEFT);
        return view('backend.inventory.create', compact('vendors','items','invoice_no'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required',
            'vendor' => 'required|exists:vendor,id',
            'items.*' => 'required|integer',
            'variants.*' => 'required',
            'quantities.*' => 'required|numeric|min:1',
            'cost_prices.*' => 'required|numeric|min:0',
            'selling_prices.*' => 'required|numeric|min:0',
            'subtotal.*' => 'required|numeric|min:0',
            'total' => 'required',
            'mode' => 'required',
        ]);
        DB::beginTransaction();
        try{
            $purchase = AddInventory::create([
                'invoice_no' => $request->invoice_no,
                'vendor_id' =>$request->vendor,
                'total' => $request->total,
                'payment_mode' => $request->mode,
                'added_by' => auth()->user()->id,
            ]);
            foreach($request->items as $i => $item_id){
                if(!$item_id) continue;
                $qty =  $request->quantities[$i] ?? 0;
                $variant =  $request->variants[$i] ?? 0;
                $cost =  $request->cost_prices[$i] ?? 0;
                $sell =  $request->selling_prices[$i] ?? 0;
                $subtotal =  $request->subtotal[$i] ?? 0;

                AddInventoryChild::create([
                    'inventory_id' => $purchase->id,
                    'item_id' => $item_id,
                    'variant' => $variant,
                    'qty' => $qty,
                    'cost_price' => $cost,
                    'selling_price'=> $sell,
                    'subtotal'=> $subtotal,
                ]);
                $inventory = Inventory::where('item_id',$item_id)->where('variant',$variant)->first();
                $previous_stock = 0;
                if($inventory){
                    $previous_stock = $inventory->stock;
                    $inventory->stock += $qty;
                    $inventory->cost_price = $cost;
                    $inventory->selling_price = $sell;
                    $inventory->save();   
                }else{
                    $inventory = Inventory::create([
                        'item_id' => $item_id,
                        'variant' => $variant,
                        'stock' => $qty,
                        'cost_price' => $cost,
                        'selling_price' => $sell,
                        'created_by' =>auth()->user()->id,
                    ]);
                }

                InventoryHistory::create([
                    'inventory_id' => $inventory->id,
                    'change_type' => 'added',
                    'quantity_changed' => $qty,
                    'previous_stock' => $previous_stock,
                    'new_stock' =>$inventory->stock,
                    'reason' => 'Purchase',    
                ]);
            }
       
            DB::commit();
            return redirect()->route('inventory.index')->with('success','Inventory added successfully !!');
        }
        catch(\Exception $e){
            DB::rollBack();
            
        \Log::error('Inventory Add Error', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
            'request' => request()->all()
        ]);
            return back()->with('error', 'Error Adding Inventory: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string $id)
    {
        $purchase = AddInventory::with(['vendor','inv_added_by','children'])->findOrFail($id);
        return view('backend.inventory.show',compact('purchase'));
    }

    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('backend.inventory.edit',compact('inventory'));
    }

    public function update(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $request->validate([
           'item_id'=> 'required',
            'variant' => 'required',
            'stock' => 'required',
            'cost_price' => 'required',
            'selling_price' => 'required',
        ]);
        $data = [
            'item_id'=> $request->item_id,
            'variant' => $request->variant,
            'stock' => $request->stock,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
        ];
        $inventory->update($data);
        return redirect()->route('item.index')->with('success','Item details Updated successfully !!');
    }

    public function destroy(string $id)
    {
        $inventory =  Inventory::findOrFail($id);
        
    }

    public function getInventoryPrice(Request $request)
{
    $inventory = Inventory::where('item_id', $request->item_id)
                          ->where('variant', $request->variant)
                          ->first();

    if ($inventory) {
        return response()->json([
            'price' => $inventory->selling_price,
            'stock' => $inventory->stock
        ]);
    }

    return response()->json(['price' => 0]);  
}

    public function donwloadInventoryInvoice($id)
{
    $purchase = AddInventory::with(['vendor', 'children.item', 'inv_added_by'])->findOrFail($id);
    $pdf = Pdf::loadView('backend.inventory.invoice', compact('purchase'))
            ->setPaper('A4', 'portrait');
    return $pdf->download('Invoice-'.$purchase->invoice_no.'.pdf');
}

}
