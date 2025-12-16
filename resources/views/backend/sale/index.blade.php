@extends('layouts.app')

@section('content')
@include('components.toast')
<div class="roles-permissions">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-gray-700 uppercase font-bold">All Sales</h2>

        <div class="flex flex-wrap items-center gap-3">

            <!-- Return Items Button -->
            <button id="openReturnModal"
                class="bg-blue-500 text-white text-sm uppercase py-2 px-4 flex items-center rounded hover:bg-blue-600 transition mx-2">
                <i class="fas fa-undo mr-2"></i>
                Return Items
            </button>

            <!-- Add New Sales Button -->
            <a href="{{ route('sale.create') }}"
                class="bg-green-500 text-white text-sm uppercase py-2 px-4 flex items-center rounded hover:bg-green-600 transition">
                <i class="fas fa-plus mr-2"></i>
                Add New Sales
            </a>

        </div>
    </div>
</div>

<!-- <pre>@json($sales)</pre> -->
<div class="mt-8 bg-white rounded border-b-4 border-gray-300 overflow-x-auto">
    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-600 text-white uppercase text-sm font-semibold">
                <th class="px-4 py-3 text-left">Bill No</th>
                <th class="px-4 py-3 text-left">Customer(Parents) Name</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Payment Mode</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Added By</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($sales as $sale)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $sale->bill_no }}</td>
                <td class="px-4 py-3">{{ $sale->parent->user->name }}</td>
                <td class="px-4 py-3">{{ $sale->total ?? '-' }}</td>
                <td class="px-4 py-3">{{ $sale->payment_mode ?? '-' }}</td>
                <td class="px-4 py-3">{{ $sale->created_at->format('d M Y') ?? '-' }}</td>
                <td class="px-4 py-3">{{ $sale->sale_done_by->name }}</td>
                <td class="px-4 py-3 text-right space-x-3">
                    <a href="{{ route('sale.edit',$sale->id) }}"
                        class="text-blue-600 hover:text-blue-800 transition transform hover:scale-125">
                        <i class="fa fa-pencil text-sm"></i>
                    </a>
                    <a href="{{ route('sale.show', $sale->id) }}"
                        class="text-gray-700 hover:text-black transition transform hover:scale-125">
                        <i class="fa fa-eye text-sm"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-8">
    {{-- $sales->links() --}}
</div>

<!-- Return Items Modal -->
<div id="returnModal"
    class="fixed inset-0 backdrop-blur-sm bg-black/10 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-700 mb-3">Return Items</h3>

        <label class="block text-sm font-semibold text-gray-600 mb-1">
            Enter Bill No
        </label>

        <input type="text" id="bill_no_input"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
            placeholder="Enter Bill Number">

        <p id="billError" class="text-red-500 text-sm mt-1 hidden"></p>

        <div class="flex justify-end gap-3 mt-4">
            <button type="button" id="closeReturnModal"
                class="px-4 py-2 text-sm bg-gray-300 rounded hover:bg-gray-400 mx-2">
                Cancel
            </button>

            <button type="button" id="searchBillBtn"
                class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                Search
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')


<script>
    $(document).ready(function() {

        // open modal
        $("#openReturnModal").click(function() {
            $("#returnModal").removeClass("hidden").addClass("flex");
        });

        // close modal
        $("#closeReturnModal").click(function() {
            $("#returnModal").addClass("hidden").removeClass("flex");
        });

        // close when clicking outside modal box
        $("#returnModal").click(function(e) {
            if (e.target.id === "returnModal") {
                $("#returnModal").addClass("hidden").removeClass("flex");
            }
        });

        $("#searchBillBtn").click(function () {
            let bill_no = $("#bill_no_input").val().trim();
            $("#billError").addClass("hidden").text("");
            if (bill_no === "") {
                $("#billError").removeClass("hidden").text("Bill number is required.");
                return;
            }
            if (isNaN(bill_no)) {
                $("#billError").removeClass("hidden").text("Bill number must be numeric.");
                return;
            }
            window.location.href = "{{ route('sale.search-billno') }}" + "?bill_no=" + bill_no;
        });


    });
</script>
@endpush