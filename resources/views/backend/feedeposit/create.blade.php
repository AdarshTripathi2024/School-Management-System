@extends('layouts.app')

@section('content')
<div class="roles">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Deposit Fees</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <a href="{{ route('feeDeposit.index') }}" 
               class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                <span class="ml-2 text-xs font-semibold">Back</span>
            </a>
        </div>
    </div>

    {{-- Class Dropdown + Search --}}
    <div class="bg-white p-6 rounded shadow-md mb-6 max-w-xl">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold">Select Class</label>
            <select id="classDropdown" 
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
                <option value="">-- Select Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                @endforeach
            </select>
        </div>  

        <div>
            <label class="block text-gray-700 font-bold">Search Student</label>
            <input type="text" id="studentSearch" 
                placeholder="Search by name, roll no..."
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
        </div>
    </div>

    {{-- Search Results --}}
    <div id="resultBox" class="bg-white p-4 rounded shadow-md max-w-xl hidden">
        <h3 class="text-lg font-bold mb-3">Search Results</h3>
        <ul id="studentList" class="space-y-2"></ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    function searchStudents() {
        let classId = $("#classDropdown").val();
        let searchText = $("#studentSearch").val().toLowerCase();
        if (classId === "" && searchText === "") {
            $("#resultBox").addClass('hidden');
            return;
        }
        $.ajax({
            url: "{{ route('feeDeposit.searchStudent') }}",
            method: "GET",
            data: {
                class_id: classId,
                search: searchText
            },
            success: function(response) {
                $("#studentList").empty();

                if (response.length === 0) {
                    $("#studentList").append(
                        `<li class="text-red-500">No students found</li>`
                    );
                } else {
                    response.forEach(student => {
                        $("#studentList").append(`
                            <li class="p-2 border rounded hover:bg-gray-100 cursor-pointer">
                                <strong>${student.name}</strong> 
                                <span class="text-gray-500">(Roll: ${student.roll_no})</span>
                            </li>
                        `);
                    });
                }

                $("#resultBox").removeClass('hidden');
            }
        });
    }

    $("#classDropdown").change(searchStudents);
    $("#studentSearch").on("keyup", searchStudents);
});
</script>

@endsection
