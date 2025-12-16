@extends('layouts.app')

@section('content')
<div class="roles-permissions">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-gray-700 uppercase font-bold">Sale Invoice</h2>

        <a href="{{ route('sale.invoice-download', $sale->id) }}"
            class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
           <i class="fas fa-download mr-2"></i> Download Sale Invoice
        </a>
    </div>
</div>
<div class="bg-white p-6 rounded-xl shadow-sm mb-8 border border-gray-200 max-w-4xl mx-auto">
    <h3 class="text-lg font-bold mb-4 text-blue-700 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 14l9-5-9-5-9 5 9 5z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 14l6.16-3.422A12.083 12.083 0 0118 20.944M12 14L5.84 10.578A12.083 12.083 0 006 20.944M12 14v7.944" />
        </svg>
        Sale Detail
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
        <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
            <span class="text-gray-500 font-medium">Bill No:</span>
            <span class="font-semibold text-gray-800">{{ $sale->bill_no }}</span>
        </div>
        <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
            <span class="text-gray-500 font-medium">Customer Name:</span>
            <span class="font-semibold text-gray-800">{{ $sale->parent->user->name }}</span>
        </div>

        <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
            <span class="text-gray-500 font-medium">Total Amount:</span>
            <span class="font-semibold text-gray-800">Rs. {{ $sale->total }}</span>
        </div>

        <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
            <span class="text-gray-500 font-medium">Payment Mode:</span>
            <span class="font-semibold text-gray-800">{{ $sale->payment_mode }}</span>
        </div>

        <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
            <span class="text-gray-500 font-medium">Date:</span>
            <span class="font-semibold text-gray-800">{{ $sale->created_at->format('d-m-Y H:i:s A') }}</span>
        </div>

        <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
            <span class="text-gray-500 font-medium">By:</span>
            <span class="font-semibold text-gray-800">{{ $sale->sale_done_by->name }}</span>
        </div>
    </div>
</div>


{{-- Responsive Table Section --}}
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 w-full">
    <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">Items Details</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm text-center">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Item</th>
                    <th class="border px-3 py-2">Variant</th>
                    <th class="border px-3 py-2">Unit Price</th>
                    <th class="border px-3 py-2">Quantity</th>
                    <th class="border px-3 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->children as $index => $child)
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">{{ $child->item->name ?? 'N/A' }}</td>
                    <td class="border px-3 py-2">{{ strtoupper($child->variant) }}</td>
                    <td class="border px-3 py-2">₹ {{ $child->price }}</td>
                    <td class="border px-3 py-2">{{ $child->qty }}</td>
                    <td class="border px-3 py-2">₹ {{ $child->subtotal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 text-right text-sm text-gray-700">
        <strong>GrandTotal:</strong> Rs. {{ $sale->total }}
    </div>
</div>
@endsection