@extends('layouts.app')

@section('content')
<div class="roles">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Add New Inventory</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <button type="button" id="openAddItemModal"
                class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-500 mx-2">
                Add New Item
            </button>
            <a href="{{ route('inventory.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path>
                </svg>
                <span class="ml-2 text-xs font-semibold">Back</span>
            </a>

        </div>
    </div>

    <div class="table w-full mt-8 bg-white rounded">
        <form action="{{ route('inventory.store') }}" method="POST" class="w-full px-6 py-12" enctype="multipart/form-data">
            @csrf
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Invoice No
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input type="text" name="invoice_no" id="invoice_no"
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700" value="{{$invoice_no}}"
                        readonly>
                    @error('invoice_no')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Select Vendor<span class="text-red-500">*</span>
                    </label>
                </div>

                <div class="md:w-2/3">
                    <div class="flex gap-2">
                        <select name="vendor" class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4">
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id}}">{{ $vendor->vendor_name }}</option>
                            @endforeach
                        </select>
                
                        <button type="button" id="openAddVendorModal"
                            class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-500 mx-2">
                            Add New
                        </button>
                    </div>
                    @error('vendor')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <h3 class="text-lg font-bold mb-3">Purchased Items</h3>
            <table class="w-full mb-4" id="itemsTable">
                <thead>
                    <tr class="border-b">
                        <th class="p-2">Item</th>
                        <th class="p-2">Variant</th>
                        <th class="p-2">Qty</th>
                        <th class="p-2">Cost Price</th>
                        <th class="p-2">Selling Price</th>
                        <th class="p-2">SubTotal</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <!-- Dynamic Rows Appears Here -->
                </tbody>
            </table>
            <button type="button" id="addRowBtn"
                class="bg-blue-600 text-white px-3 py-2 rounded mb-4">
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
                        class="bg-gray-200 border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700"
                        readonly>
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
    <div id="itemModal"
        class="fixed inset-0 hidden backdrop-blur-md bg-white/30 flex items-center justify-center z-50">
        <div id="itemModalContent" class="bg-white w-96 p-6 rounded shadow relative">
            <h2 class="text-xl font-bold mb-4">Add New Item</h2>

            <form id="addItemForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Item Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded bg-gray-100">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Description</label>
                    <input type="text" name="description" class="w-full px-3 py-2 border rounded bg-gray-100">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeItemModal"
                        class="px-3 py-2 bg-gray-500 text-white rounded mx-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-3 py-2 bg-blue-600 text-white rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="vendorModal"
        class="fixed inset-0 hidden backdrop-blur-md bg-white/30 flex items-center justify-center z-50">
        <div id="vendorModalContent" class="bg-white w-96 p-6 rounded shadow relative">
            <h2 class="text-xl font-bold mb-4">Add New Vendor</h2>

            <form action="{{route('vendor.store')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">Vendor Name</label>
                    <input type="text" name="vendor_name" class="w-full px-3 py-2 border rounded bg-gray-100">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Vendor Mobile</label>
                    <input type="text" name="mobile" class="w-full px-3 py-2 border rounded bg-gray-100">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Address</label>
                    <input type="text" name="address" class="w-full px-3 py-2 border rounded bg-gray-100">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeVendorModal"
                        class="px-3 py-2 bg-gray-500 text-white rounded mx-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-3 py-2 bg-blue-600 text-white rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>


</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // OPEN MODALS
    $("#openAddItemModal").click(function() {
        $("#itemModal").removeClass("hidden");
    });

    $("#openAddVendorModal").click(function() {
        $("#vendorModal").removeClass("hidden");
    });

    // CLOSE MODALS
    $("#closeItemModal").click(function() {
        $("#itemModal").addClass("hidden");
    });

    $("#closeVendorModal").click(function() {
        $("#vendorModal").addClass("hidden");
    });

    // CLOSE WHEN CLICKING OUTSIDE ITEM MODAL
    $(document).click(function(e) {
        if (
            $("#itemModal").is(":visible") &&
            !$(e.target).closest("#itemModalContent").length &&
            !$(e.target).closest("#openAddItemModal").length
        ) {
            $("#itemModal").addClass("hidden");
        }
    });

    // CLOSE WHEN CLICKING OUTSIDE VENDOR MODAL
    $(document).click(function(e) {
        if (
            $("#vendorModal").is(":visible") &&
            !$(e.target).closest("#vendorModalContent").length &&
            !$(e.target).closest("#openAddVendorModal").length
        ) {
            $("#vendorModal").addClass("hidden");
        }
    });
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
                    <input type="number" name="quantities[]" class="qty-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <input type="number" name="cost_prices[]" class="costprice-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <input type="number" name="selling_prices[]" class="sellingprice-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <input type="number" name="subtotal[]" class="subtotal-input bg-gray-200 border rounded w-full py-2 px-2">
                </td>

                <td class="p-2">
                    <button type="button" class="removeRow bg-red-600 text-white px-3 py-1 rounded">X</button>
                </td>
            </tr>
        `;
            $("#itemsBody").append(row);
        });

         $(document).on("keyup change", ".qty-input, .costprice-input", function() {
            let row = $(this).closest("tr");
            let qty = parseFloat(row.find(".qty-input").val()) || 0;
            let price = parseFloat(row.find(".costprice-input").val()) || 0;
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
    $(document).ready(function() {
        $("#addItemForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('item.store_ajax') }}", 
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {

                    // Close Modal
                    $("#addItemModal").fadeOut();

                    // Alert Success
                    alert("Item added successfully!");

                    // Reload Page
                    location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Error: Could not add item.");
                }
            });
        });
    });
</script>

@endsection