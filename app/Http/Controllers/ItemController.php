<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Inventory;

class ItemController extends Controller
{

    public function index()
    {
        $items = Inventory::with(['inv_created_by','item'])->paginate(10);
        return view('backend.item.index',compact('items'));
    }

 
    public function create()
    {
        return view('backend.item.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'nullable',
        ]);
        Item::create([
            'name'=> $request->name,
            'description'=> $request->description,
        ]);
        return redirect()->route('item.index')->with('success','New Item added Successfully !!');
    }

    public function storeItemAjax(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'nullable',
        ]);
        Item::create([
            'name'=> $request->name,
            'description'=> $request->description,
        ]);
       return response()->json([
            'success' => true,
            'message' => 'Item added successfully!'
        ]);
    }

    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        return view('backend.item.edit',compact('item'));
    }

 
    public function update(Request $request, string $id)
    {
        $item =Item::findOrFail($id);
        $request->validate([
            'name'=>'required',
            'description'=>'nullable',
        ]);
        $data =[
            'name'=>$request->name,
            'description'=> $request->description,
        ];
        $item->update($data);
        return redirect()->route('item.index')->with('success','Item detail Updated successfully !!');
    }

    public function destroy(string $id)
    {
        //
    }
}
