@extends('layouts.app')

@section('content')
<div class="roles-permissions">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-gray-700 uppercase font-bold">Result Summary</h2>

        <a href="{{ route('result.download', $result->id) }}"
            class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
            <i class="fas fa-download mr-2"></i> Download Result
        </a>
    </div>
    <!-- <pre>@json($result)</pre> -->
    {{-- Student & Exam Info --}}
    <div class="bg-white p-6 rounded-xl shadow-sm mb-8 border border-gray-200 max-w-4xl mx-auto">
        <h3 class="text-lg font-bold mb-4 text-blue-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 14l6.16-3.422A12.083 12.083 0 0118 20.944M12 14L5.84 10.578A12.083 12.083 0 006 20.944M12 14v7.944" />
            </svg>
            Student & Exam Summary
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
            <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
                <span class="text-gray-500 font-medium">Student Name:</span>
                <span class="font-semibold text-gray-800">{{ $result->student->user->name }}</span>
            </div>

            <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
                <span class="text-gray-500 font-medium">Roll Number:</span>
                <span class="font-semibold text-gray-800">{{ $result->student->roll_number ?? '-' }}</span>
            </div>

            <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
                <span class="text-gray-500 font-medium">Class:</span>
                <span class="font-semibold text-gray-800">{{ $result->class->class_name }}</span>
            </div>

            <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
                <span class="text-gray-500 font-medium">Exam:</span>
                <span class="font-semibold text-gray-800">{{ $result->exam->exam_name }}</span>
            </div>

            <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
                <span class="text-gray-500 font-medium">Total Marks:</span>
                <span class="font-semibold text-gray-800">{{ $result->total }}</span>
            </div>

            <div class="bg-gray-50 px-4 py-2.5 rounded-lg flex justify-between items-center">
                <span class="text-gray-500 font-medium">Obtained Marks:</span>
                <span class="font-semibold text-green-600">{{ $result->grandtotal }}</span>
            </div>

            <div class="bg-gray-50 px-4 py-3 rounded-lg flex justify-between items-center sm:col-span-2">
                <span class="text-gray-500 font-medium">Percentage:</span>
                <span class="font-semibold text-blue-700 text-base">
                    {{ number_format($result->percentage, 2) }}%
                </span>
            </div>
        </div>
    </div>


    {{-- Responsive Table Section --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 w-full">
        <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">Subject Wise Marks</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2">Subject</th>
                        <th class="border px-3 py-2">Theory Total</th>
                        <th class="border px-3 py-2">Obtained Theory</th>
                        <th class="border px-3 py-2">Practical Total</th>
                        <th class="border px-3 py-2">Obtained Practical</th>
                        <th class="border px-3 py-2">Total Marks</th>
                        <th class="border px-3 py-2">Obtained Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result->subjectMarks as $index => $mark)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2">{{ $mark->subject->name ?? 'N/A' }}</td>
                        <td class="border px-3 py-2">{{ $mark->theory_total }}</td>
                        <td class="border px-3 py-2">{{ $mark->obtained_theory }}</td>
                        <td class="border px-3 py-2">{{ $mark->practical_total }}</td>
                        <td class="border px-3 py-2">{{ $mark->obtained_practical }}</td>
                        <td class="border px-3 py-2 font-semibold">{{ $mark->total_marks }}</td>
                        <td class="border px-3 py-2 font-semibold text-green-600">{{ $mark->obtained_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right text-sm text-gray-700">
            <strong>Total:</strong> {{ $result->grandtotal }} / {{ $result->total }}
            &nbsp;|&nbsp;
            <strong>Percentage:</strong> {{ number_format($result->percentage, 2) }}%
        </div>
    </div>
    @endsection