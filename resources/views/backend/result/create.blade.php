@extends('layouts.app')

@section('content')
@include('components.toast')
<div class="roles">
   <div class="flex items-center justify-between mb-6">
    <div class="flex items-center space-x-4">
        <h2 class="text-gray-700 uppercase font-bold">Upload Result for Exam</h2>

        <select id="examSelect" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
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
    {{-- LOOP THROUGH EACH CLASS --}}
    @foreach($classData as $data)
        <div class="bg-white p-4 rounded shadow mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-3">
                Class: {{ $data['class']->class_name }}
            </h3>

            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Student Name</th>
                        <th class="p-2 border">Roll No</th>
                        <th class="p-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['students'] as $index => $student)
                        <tr class="text-center">
                            <td class="border p-2">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $student->user->name }}</td>
                            <td class="border p-2">{{ $student->roll_number ?? '-' }}</td>
                            <td class="border p-2 text-center">
                                @if($student->result_uploaded)
                                    <span class="text-green-700 bg-green-100 border border-green-300 px-2 py-0.5 rounded text-xs font-semibold">
                                        Already Uploaded
                                    </span>
                                    <a href="{{ route('result.show', $student->result_id) }}" 
                                    class="bg-blue-600 text-white px-2 py-1 rounded text-xs ml-2">
                                    <i class="fas fa-eye"></i> Show
                                    </a>
                                @else
                                    <button class="toggle-subjects bg-blue-600 text-white px-2 py-1 rounded text-xs"
                                            data-student-id="{{ $data['class']->id }}-{{ $student->id }}">
                                        <i class="fas fa-upload"></i> Upload Marks
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <tr id="subjects-row-{{ $data['class']->id }}-{{ $student->id }}" class="hidden">
                            <td colspan="4" class="border p-3 bg-gray-50">
                                <form action="{{ route('result.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <input type="hidden" name="grade_id" value="{{ $data['class']->id }}">
                                    <input type="hidden" name="exam_id" class="exam-id-field" value="{{$exam->id}}">
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
                                          @foreach($data['subjects'] as $subject)
                                            <tr>
                                                <td class="border p-1">{{ $subject->name }}</td>

                                                <td><input type="number" name="theory_max[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center" value="75"></td>
                                                <td><input type="number" name="theory_obtained[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center"></td>

                                                <td><input type="number" name="practical_max[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center" value="25"></td>
                                                <td><input type="number" name="practical_obtained[{{ $subject->id }}]" class="marks-input w-20 border p-1 text-center"></td>

                                                <td><input type="number" name="total_max[{{ $subject->id }}]" readonly class="border bg-gray-100 w-20 text-center" value="100"></td>
                                                <td><input type="number" name="total_obtained[{{ $subject->id }}]" readonly class="border bg-gray-100 w-20 text-center"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-100 font-semibold">
                                            <tr>
                                                <td colspan="5" class="text-right p-1 border">Total:</td>
                                                <td class="text-center border p-1"><span class="max-total">0</span></td>
                                                <td class="text-center border p-1"><span class="grand-total">0</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="text-right border p-2">
                                                    Percentage: <span class="percentage">0%</span>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
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
