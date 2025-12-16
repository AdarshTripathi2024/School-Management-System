@extends('layouts.app')

@section('content')
@include('components.toast')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<div class="roles">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Add New Sale</h2>
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
        <form action="{{ route('sale.store') }}" method="POST" class="w-full px-6 py-12" enctype="multipart/form-data">
            @csrf
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Bill No <span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="bill_no" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('bill_no', $bill_no) }}" readonly>
                    @error('bill_no')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Parents Name <span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <select name="parent_id" id="parentSelect" type="text" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                        <option value="">Select Parents</option>
                        @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{$parent->user->name}}</option>
                        @endforeach
                    </select>
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
                            <th class="p-2 w-[120px]">Stock</th>
                            <th class="p-2 w-[100px]">Qty</th>
                            <th class="p-2 w-[120px]">Price</th>
                            <th class="p-2 w-[150px]">Sub Total</th>
                            <th class="p-2 w-[100px]">Action</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody"></tbody>
                </table>
            </div>

            <button type="button" id="addRowBtn"
                class="bg-blue-600 text-white px-3 py-2 rounded my-4">
                + Add Item
            </button>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Total
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="total" id="total"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700">
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Payment Mode
                    </label>
                </div>
                <div class="md:w-2/3">
                    <select name="mode" id="mode"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700">
                        <option value="">Select</option>
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                    </select>
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
    $(function() {
        $("#datepicker-sc").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    })
</script>

<script>
    function calculateTotal() {
        let total = 0;
        $(".subtotal-input").each(function() {
            let v = parseFloat($(this).val()) || 0;
            total += v;
        });
        $("#total").val(total.toFixed(2));
    }
    $(document).ready(function() {

        $("#addRowBtn").click(function() {
            let row = `
            <tr class="border-b">
                <td class="p-2 w-[300px]">
                    <select name="items[]" class="item-select bg-gray-200 border rounded w-[300px] py-2 px-2">
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>

                <td class="p-2">
                    <select name="variants[]" class="variant-select bg-gray-200 border rounded w-full py-2 px-2">
                        <option value="sm">S</option>
                        <option value="m">M</option>
                        <option value="l">L</option>
                        <option value="xl">XL</option>
                    </select>
                </td>

                <td class="p-2">
                    <input type="number" name="stocks[]" class="stock-input bg-gray-200 border rounded w-full py-2 px-2" readonly>
                </td>

                <td class="p-2">
                    <input type="number" name="quantities[]" class="qty-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <input type="number" name="prices[]" class="price-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <input type="number" name="sub_total[]" class="subtotal-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <button type="button" class="removeRow bg-red-600 text-white px-3 py-1 rounded">X</button>
                </td>
            </tr>
        `;
            $("#itemsBody").append(row);
        });

        $(document).on("change", ".item-select, .variant-select", function() {
            let row = $(this).closest("tr");
            let item_id = row.find(".item-select").val();
            let variant = row.find(".variant-select").val();
            if (item_id && variant) {
                $.ajax({
                    url: "{{ route('inventory.price') }}",
                    type: "GET",
                    data: {
                        item_id: item_id,
                        variant: variant
                    },
                    success: function(res) {
                        row.find(".price-input").val(res.price);
                        row.find(".stock-input").val(res.stock);

                        // recalculate subtotal
                        let qty = parseFloat(row.find(".qty-input").val()) || 0;
                        let subtotal = qty * res.price;
                        row.find(".subtotal-input").val(subtotal.toFixed(2));

                        calculateTotal();
                    }
                });
            }
        });

        // When qty or price changes → update subtotal
        $(document).on("keyup change", ".qty-input, .price-input", function() {
            let row = $(this).closest("tr");
            let qty = parseFloat(row.find(".qty-input").val()) || 0;
            let price = parseFloat(row.find(".price-input").val()) || 0;
            let subtotal = qty * price;
            row.find(".subtotal-input").val(subtotal.toFixed(2));
            calculateTotal();
        });

        // Remove row
        $(document).on("click", ".removeRow", function() {
            $(this).closest("tr").remove();
        });

    });
</script>
<script>
    new TomSelect("#parentSelect", {
        create: false,
        maxOptions: 500,
        placeholder: "Search Parents...",
    });
</script>

@endpush