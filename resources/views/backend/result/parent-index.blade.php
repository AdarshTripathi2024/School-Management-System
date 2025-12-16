@extends('layouts.app')
@section('content')
@include('components.toast')

<div class="roles-permissions">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-gray-700 uppercase font-bold">Student Report For Each Exam</h2>

        <a href="{{ route('home') }}"
            class="bg-green-500 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
            <i class="fa fa-arrow"></i>
            <span class="ml-2 text-xs font-semibold">Back</span>
        </a>
    </div>
    <div class="flex border-b mb-4">
        @foreach($exams as $exam)
        <a href="#exam-{{ $exam->id }}"
            class="exam-tab px-4 py-2 text-sm font-semibold uppercase border-b-2      
                  hover:border-gray-700 hover:text-gray-700">
            {{ $exam->exam_name }}
        </a>
        @endforeach
    </div>


    <!-- Exam Cards -->
    @foreach($exams as $exam)
    <div id="exam-{{ $exam->id }}" class="exam-panel mb-8 bg-white rounded shadow p-4 border">

        <h3 class="text-gray-700 font-bold text-lg mb-4">
            {{ $exam->exam_name }}
        </h3>

        @php
            $examResults = $resultsGrouped[$exam->id] ?? collect();
        @endphp


        @if($examResults->count() > 0)
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-600 text-white text-sm uppercase">
                    <th class="px-3 py-2 text-left">Student Name</th>
                    <th class="px-3 py-2 text-left">Class</th>
                    <th class="px-3 py-2 text-left">Obtained marks</th>
                    <th class="px-3 py-2 text-left">Total marks</th>
                    <th class="px-3 py-2 text-left">Percentage</th>
                    <th class="px-3 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
               @foreach($user->parent->children as $child)
                   @php 
                        // Check if this child has result in this exam
                        $result = $examResults[$child->id] ?? null;
                    @endphp
                <tr class="border-b">
                    <td class="px-3 py-2">
                        {{ $child->user->name }}
                    </td>
                    <td class="px-3 py-2">
                        {{ $result->class->class_name ?? '-' }}
                    </td>
                    <td class="px-3 py-2">
                        {{ $result->grandtotal ?? '-' }}
                    </td>
                    <td class="px-3 py-2">
                        {{ $result->total ?? '-' }}
                    </td>
                    <td class="px-3 py-2">
                     {{ $result?->percentage !== null ? $result?->percentage . '%' : '-' }}
                    </td>
                    <td class="px-3 py-2 text-right flex justify-end gap-1">
                        @if($result)
                            <a href="{{ route('result.download', [
                                    'id' => $result->id
                                ]) }}"
                                class="bg-blue-600 p-1 rounded-sm text-white mx-2" title="View">
                                <i class="fa fa-download"></i>
                            </a>
                            <a href="{{ route('exam.student-result', [
                                    'result_id' => $result->id,
                                ]) }}"
                                class="bg-blue-600 p-1 rounded-sm text-white" title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                        @else
                            <span class="text-gray-500 text-sm">No Result</span>
                        @endif

                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-sm">No results uploaded for this exam.</p>
        @endif
    </div>
    @endforeach

    @include('backend.modals.delete',['name' => 'result'])
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

    // Hide all exam panels
    document.querySelectorAll('.exam-panel').forEach(panel => panel.style.display = 'none');

    // Show the first exam by default
    const first = document.querySelector('.exam-panel');
    if (first) first.style.display = 'block';

    // When a tab is clicked
    document.querySelectorAll('.exam-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            // Hide all panels
            document.querySelectorAll('.exam-panel').forEach(panel => panel.style.display = 'none');
            // Show the selected one
            const target = this.getAttribute('href');
            document.querySelector(target).style.display = 'block';
        });
    });

    });
</script>

@endsection