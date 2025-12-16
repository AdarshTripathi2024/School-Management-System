<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Parents;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Item;
use App\Models\SaleChild;
use App\Models\ReturnBill;
use App\Models\ReturnBillChild;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['parent', 'sale_done_by'])->paginate(10);
        return view('backend.sale.index', compact('sales'));
    }

    public function create()
    {
        $sale_count = Sale::count() + 1;
        $year = date('Y');
        $bill_no = 'BILL' . $year . str_pad($sale_count, 4, '0', STR_PAD_LEFT);
        $parents = Parents::with('user')->get();
        $items = Item::orderBy('id', 'DESC')->get();
        return view('backend.sale.create', compact('bill_no', 'parents', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_id'   => 'required',
            'bill_no'     => 'required',
            'total'       => 'required',
            'items'       => 'required|array',
            'items.*'     => 'required|numeric',
            'variants'    => 'required|array',
            'variants.*'  => 'required|string',
            'prices'      => 'required|array',
            'prices.*'    => 'required|numeric',
            'quantities'  => 'required|array',
            'quantities.*' => 'required|numeric',
            'sub_total'   => 'required|array',
            'sub_total.*' => 'required|numeric',
            'stocks'      => 'required|array',
            'stocks.*'    => 'required|numeric',
            'mode'        => 'required',
        ]);

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'bill_no' => $request->bill_no,
                'parent_id' => $request->parent_id,
                'total' => $request->total,
                'payment_mode' => $request->mode,
                'created_by' => auth()->user()->id,
            ]);
            foreach ($request->items as $i => $item_id) {
                if (!$item_id) continue;
                $qty = $request->quantities[$i] ?? 0;
                $price = $request->prices[$i] ?? 0;
                $subtotal = $request->sub_total[$i] ?? 0;
                $variant = $request->variants[$i] ?? 0;
                SaleChild::create([
                    'sale_id' => $sale->id,
                    'item_id' => $item_id,
                    'variant' => $variant,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'qty' => $qty,
                ]);
                $inventory = Inventory::where('item_id', $item_id)->where('variant', $variant)->first();
                $previous_stock = 0;
                if ($inventory) {
                    $previous_stock = $inventory->stock;
                    // reduce stock
                    $inventory->update([
                        'stock' => $inventory->stock - $qty,
                    ]);
                } else {
                    throw new \Exception("Inventory record not found for item $item_id (variant $variant)");
                }
                InventoryHistory::create([
                    'change_type' => 'sold',
                    'quantity_changed' => $qty,
                    'previous_stock' => $previous_stock,
                    'new_stock' => $inventory->stock,
                    'reason' => 'Sale',
                    'related_sale_id' => $sale->id,
                ]);
            }
            DB::commit();
            return redirect()->route('sale.index')->with('success', 'Sale Added Successfully !!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Sale Add Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request' => request()->all(),
            ]);
            return back()->with('error', 'Error Adding Sale: ' . $e->getMessage())->withInput();
        }
    }


    public function show(string $id)
    {
        $sale = Sale::with(['parent.user', 'children.item'])->findOrFail($id);
        return view('backend.sale.show', compact('sale'));
    }

    public function edit(string $id)
    {
        $sale = Sale::findOrFail($id);
        return view('backend.sale.edit', compact('sale'));
    }


    public function update(Request $request, string $id) {}



    public function donwloadSaleInvoice($id)
    {
        $sale = Sale::with(['parent.user', 'children.item', 'sale_done_by'])->findOrFail($id);

        $pdf = Pdf::loadView('backend.sale.invoice', compact('sale'))
            ->setPaper('A4', 'portrait');
        return $pdf->download('Invoice-' . $sale->bill_no . '.pdf');
    }
 


    public function searchBillNo(Request $request)
    {
        $bill_no = $request->get('bill_no');
        $sale_data = Sale::with(['children', 'parent'])->where('bill_no', $bill_no)->first();
        $items = Item::orderBy('id', 'DESC')->get();
        if ($sale_data) {
            return view('backend.sale.return', [
                'sale' => $sale_data,
                'items' => $items
            ]);
        }
        return redirect()->back()->with('error', 'Bill not found!');
    }

    public function storeReturnSaleItems(Request $request)
    {
        $request->validate([
            'parent_id'   => 'required',
            'bill_no'     => 'required',
            'total'       => 'required',
            'totalRefund' => 'required',
            'item_id'     => 'required|array',
            'item_id.*'   => 'required|numeric',
            'child_id'     => 'required|array',
            'child_id.*'   => 'required|numeric',
            'variant'     => 'required|array',
            'variant.*'   => 'required|string',
            'price'       => 'required|array',
            'price.*'     => 'required|numeric',
            'qty'         => 'required|array',
            'qty.*'       => 'required|numeric',
            'refund_amount'    => 'required|array',
            'refund_amount.*' => 'nullable|numeric',
            'ret_qty.*'    => 'nullable|numeric',
        ]);
        DB::beginTransaction();
        try {
            $bill_no = $request->bill_no;
            $remark = $request->remark;
            $total_refund = $request->totalRefund;
            $partial = false;
            $sale = Sale::where('bill_no', $bill_no)->first();

            if (!$sale) {
                return redirect()->to('sale.index')->with('error', "No Sale found with bill no $bill_no");
            }

            $return = ReturnBill::create([
                'bill_no' => $bill_no,
                'remark' => $remark,
                'total_refund' => $total_refund,
                'taken_by' => auth()->user()->id,
            ]);

            foreach ($request->child_id as $i => $row) {
                $ret_qty = $request->ret_qty[$i];
                $qty = $request->qty[$i];

                if ($ret_qty > $qty) {
                    throw new \Exception("Returned quantity cannot exceed sold quantity.");
                }

                if ($ret_qty == 0 || $ret_qty == null) {
                    $partial = true;
                    continue;
                } else {
                    $child_id = $request->child_id[$i];
                    $item_id = $request->item_id[$i];
                    $price = $request->price[$i];
                    $variant = $request->variant[$i];
                    $ret_amount = $request->refund_amount[$i];
                    $sale_child = SaleChild::find($child_id);
                    $sale_child->update([
                        'returned_quantity' => $ret_qty,
                    ]);
                    if($ret_qty < $sale_child->qty)
                    {
                        $partial = true;
                    }
                    ReturnBillChild::create([
                        'return_id' => $return->id,
                        'item_id' => $item_id,
                        'variant' => $variant,
                        'qty' => $ret_qty,
                        'unit_price' => $price,
                        'total' => $ret_amount,
                    ]);
                    $inventory = Inventory::where(['item_id' => $item_id, 'variant' => $variant])->first();
                    if (!$inventory) {
                        throw new \Exception("Inventory record not found for item ID: $item_id ($variant)");
                    }

                    $prev_qty = $inventory->stock;
                    $new_stock = $prev_qty + $ret_qty;

                    $inventory->update([
                        'stock' => $new_stock,
                    ]);

                    InventoryHistory::create([
                        'inventory_id' => $inventory->id,
                        'change_type' => 'returned',
                        'quantity_changed' => $ret_qty,
                        'previous_stock' => $prev_qty,
                        'new_stock' => $new_stock,
                        'reason' => 'Return',
                        'related_sale_id' => $sale->id
                    ]);
                }
            }

            if($partial)
             {
                $sale->update([
                    'is_return' => 2,
                    'return_taken_by' => auth()->user()->id,
                ]);
             }else{
                $sale->update([
                    'is_return' => 1,
                    'return_taken_by' => auth()->user()->id,
                ]);
             }
            DB::commit();

            return redirect()->route('sale.index')->with('success', 'Return Bill submitted Successfully!!');
        
        } catch (\Exception $e) {

            DB::rollBack();
              \Log::error('Sale Add Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),  
                'request' => request()->all(),
            ]);
            return redirect()->route('sale.index')
                ->with('error', "Error processing return: " . $e->getMessage());
        }
    }

    public function returnIndex(Request $request)
    {
        $returns = ReturnBill::with([ 'return_by', 'saleBill' ])->orderBy('created_at','DESC')->paginate(10);
        return view('backend.sale.return-index', compact('returns'));
    }

    public function showReturnDetails(string $id)
    {
        $return = ReturnBill::with([ 'returnChildren','return_by', 'saleBill' ])->first();
        return view('backend.sale.show-return', compact('return'));
    }

       public function donwloadReturnInvoice($id)
    {
        $return = ReturnBill::with([ 'returnChildren','return_by', 'saleBill' ])->first();

        $pdf = Pdf::loadView('backend.sale.return-invoice', compact('return'))
            ->setPaper('A4', 'portrait');
        return $pdf->download('ReturnInvoice-' . $return->bill_no . '.pdf');
    }
}
