@extends('layouts.app')

@section('content')
@include('components.toast')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<div class="roles">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Return Bill</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <a href="{{ route('sale.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path>
                </svg>
                <span class="ml-2 text-xs font-semibold">Back</span>
            </a>
        </div>
    </div>
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong class="font-bold">There are some problems with your input:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="table w-full mt-8 bg-white rounded">
        <form action="{{ route('sale.store-return') }}" method="POST" class="w-full px-6 py-12" enctype="multipart/form-data">
            @csrf
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Bill No <span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="bill_no" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('bill_no', $sale->bill_no) }}" readonly>
                    @error('bill_no')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="parent_id" id="parent_id"
                        class="hidden"
                        value="{{ $sale->parent->user->name}}"><span class="text-center">{{ $sale->parent->user->name ?? '' }}</span>
                    @error('parent_id')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <h3 class="text-lg font-bold mb-3">Purchased Items</h3>
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-max border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 w-[300px]">Item</th>
                            <th class="p-2 w-[120px]">Variant</th>
                            <th class="p-2 w-[100px]">Qty</th>
                            <th class="p-2 w-[120px]">Price</th>
                            <th class="p-2 w-[150px]">Sub Total</th>
                            <th class="p-2 w-[100px]">Return Quantity</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody">
                        @foreach($sale->children as $child)
                        <tr>
                            <td class="text-center bg-gray-200 border rounded w-[300px] py-2 px-2"> <input type="hidden" name="child_id[]" value="{{ $child->id}}"><input type="hidden" name="item_id[]" value="{{ $child->item_id }}">
                                <span class="text-center">{{ $child->item->name ?? 'Unknown Item' }}</span>
                            </td>
                            <td class="text-center bg-gray-200 border rounded w-[300px] py-2 px-2"> <input type="hidden" name="variant[]" value="{{ $child->variant }}">
                                <span class="text-center"> {{ strtoupper($child->variant) }}</span>
                            </td>
                            <td class="text-center bg-gray-200 border rounded w-[300px] py-2 px-2"> <input type="hidden" name="qty[]" value="{{ $child->qty }}">
                                <span class="text-center">{{ $child->qty ?? '' }}</span>
                            </td>
                            <td class="text-center bg-gray-200 border rounded w-[300px] py-2 px-2"> <input type="hidden" name="price[]" value="{{ $child->price }}">
                                <span class="text-center">{{ $child->price ?? '' }}</span>
                            </td>
                            <td class="text-center bg-gray-200 border rounded w-[300px] py-2 px-2"> <input type="hidden" name="subtotal[]" value="{{ $child->subtotal }}">
                                <span class="text-center">{{ $child->subtotal ?? '' }}</span>
                            </td>
                            <td class="text-center bg-gray-200 border rounded w-[300px] py-2 px-2"> <input type="number" name="ret_qty[]" value="" min="0" max="{{ $child->qty }}">
                                <input type="hidden" name="refund_amount[]" value="0">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:flex md:items-center my-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Total
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="total" id="total"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700"
                        value="{{ $sale->total}}" readonly>
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Payment Mode
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="payment_mode" id="payment_mode"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700"
                        value="{{ $sale->payment_mode}}" readonly>
                </div>
            </div>
            <div class="md:flex md:items-center my-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Refund Amount
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="totalRefund" id="totalRefund"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700"
                        readonly>
                </div>
            </div>
            <div class="md:flex md:items-center my-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Remark
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="remark" id="remark"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700">
                </div>
            </div>
            <div class="md:flex md:items-center">
                <div class="md:w-1/3"></div>
                <div class="md:w-2/3">
                    <button class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).on('input', 'input[name="ret_qty[]"]', function() {
        let row = $(this).closest('tr');
        let maxQty = parseInt($(this).attr("max"));
        let entered = parseInt($(this).val()) || 0;
        let price = parseFloat(row.find('input[name="price[]"]').val());
        if (entered > maxQty) {
            alert("Return quantity cannot be greater than sold quantity!");
            $(this).val(0);
            entered = 0;
        }
        let refund = entered * price;
        console.log(refund);
        row.find('input[name="refund_amount[]"]').val(refund);
        calculateTotalRefund();
    });
    function calculateTotalRefund() {
        let total = 0;
        $('input[name="refund_amount[]"]').each(function() {
            total += parseFloat($(this).val()) || 0;
        });

        $('#totalRefund').val(total.toFixed(2));
    }
</script>


@endpush