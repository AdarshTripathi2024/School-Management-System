@extends('layouts.app')

@section('content')
@include('components.toast')
<div class="roles">
   <div class="flex items-center justify-between mb-6">
    <div class="flex items-center space-x-4">
        <h2 class="text-gray-700 uppercase font-bold">Edit Student Report</h2>

        <select id="examSelect" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                <option value="{{ $result->exam->id }}">{{ $result->exam->exam_name }}</option>
        </select>
    </div>

    <a href="{{ route('result.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
        <i class="fas fa-arrow-left"></i>
        <span class="ml-2 text-xs font-semibold">Back</span>
    </a>
</div>
 
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Whoops!</strong> There were some problems with your input.
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        <div class="bg-white p-4 rounded shadow mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                Class: {{ $result->class->class_name }}
            </h3>

            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        
                        <th class="p-2 border">Student Name</th>
                        <th class="p-2 border">Roll No</th>
                    </tr>
                </thead>
                <tbody>
                        <tr class="text-center">
                            <td class="border p-2">{{ $result->student->user->name }}</td>
                            <td class="border p-2">{{ $result->student->roll_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border p-3 bg-gray-50">
                                <form action="{{ route('result.update', $result->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- <input type="hidden" name="student_id" value="{{ $result->student->id }}">
                                    <input type="hidden" name="grade_id" value="{{ $result->class->id }}">
                                    <input type="hidden" name="exam_id" class="exam-id-field" value="{{$result->exam->id}}"> -->
                                    <table class="w-full border-collapse border border-gray-300 text-sm text-center align-middle">
                                        <thead>
                                            <tr class="bg-gray-200">
                                                <th class="border p-1">Subject</th>
                                                <th class="border p-1">Theory Max Marks</th>
                                                <th class="border p-1">Obtained Theory Marks</th>
                                                <th class="border p-1">Practical Max Marks</th>
                                                <th class="border p-1">Obtained Practical Marks</th>
                                                <th class="border p-1">Total Max Marks</th>
                                                <th class="border p-1">Obtained Total Marks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($result->subjectMarks as $subject)
                                          <!-- <pre>@json($subject)<pre> -->
                                            <tr>
                                                <td class="border p-1">{{ $subject->subject->name }}</td>

                                                <td><input type="number" name="theory_max[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center" value="{{$subject->theory_total}}"></td>
                                                <td><input type="number" name="theory_obtained[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center" value="{{$subject->obtained_theory}}"></td>

                                                <td><input type="number" name="practical_max[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center" value="{{ $subject->practical_total }}"></td>
                                                <td><input type="number" name="practical_obtained[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center" value="{{ $subject->obtained_practical }}"></td>

                                                <td><input type="number" name="total_max[{{ $subject->id }}]" readonly class="border bg-gray-100 w-20 text-center" value="{{ $subject->total_marks }}"></td>
                                                <td><input type="number" name="total_obtained[{{ $subject->id }}]" readonly class="border bg-gray-100 w-20 text-center" value="{{$subject->obtained_total }}"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-100 font-semibold">
                                            <tr>
                                                <td colspan="5" class="text-right p-1 border">Total:</td>
                                                <td class="text-center border p-1"><span class="max-total">{{$result->total}}</span></td>
                                                <td class="text-center border p-1"><span class="grand-total">{{$result->grandtotal}}</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="text-right border p-2">
                                                    Percentage: <span class="percentage">{{ $result->percentage }}%</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="mt-2 text-right">
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- jQuery Toggle Logic --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // Toggle student subject section
    $('.toggle-subjects').on('click', function() {
        const id = $(this).data('student-id');
        $('#subjects-row-' + id).toggleClass('hidden');
    });

    // Handle exam dropdown change
    $('#examSelect').on('change', function () {
        const selectedExamId = $(this).val();
        $('.exam-id-field').val(selectedExamId);
    });

    // --- Calculation Logic Below ---

    function updateSubjectTotals(row) {
        let theoryMax = parseFloat(row.find('input[name^="theory_max"]').val()) || 0;
        let pracMax   = parseFloat(row.find('input[name^="practical_max"]').val()) || 0;
        let theoryObt = parseFloat(row.find('input[name^="theory_obtained"]').val()) || 0;
        let pracObt   = parseFloat(row.find('input[name^="practical_obtained"]').val()) || 0;

        if (theoryObt > theoryMax) {
            alert("Obtained theory marks cannot exceed maximum marks!");
            row.find('input[name^="theory_obtained"]').val('');
            theoryObt = 0;
        }

        if (pracObt > pracMax) {
            alert("Obtained practical marks cannot exceed maximum marks!");
            row.find('input[name^="practical_obtained"]').val('');
            pracObt = 0;
        }

        const totalMax = theoryMax + pracMax;
        const totalObt = theoryObt + pracObt;

        row.find('input[name^="total_max"]').val(totalMax);
        row.find('input[name^="total_obtained"]').val(totalObt);
    }

    function updateGrandTotals(form) {
        let totalMaxSum = 0;
        let totalObtSum = 0;

        form.find('input[name^="total_max"]').each(function() {
            totalMaxSum += parseFloat($(this).val()) || 0;
        });
        form.find('input[name^="total_obtained"]').each(function() {
            totalObtSum += parseFloat($(this).val()) || 0;
        });

        const percentage = totalMaxSum > 0 ? ((totalObtSum / totalMaxSum) * 100).toFixed(2) : 0;
        form.find('.max-total').text(totalMaxSum);
        form.find('.grand-total').text(totalObtSum);
        form.find('.percentage').text(percentage + '%');
    }

    $(document).on('input', '.marks-input', function() {
        const row = $(this).closest('tr');
        const form = $(this).closest('form');
        updateSubjectTotals(row);
        updateGrandTotals(form);
    });

});
</script>


<style>
.hidden { display: none; }
</style>
@endsection
