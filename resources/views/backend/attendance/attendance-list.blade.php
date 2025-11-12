@extends('layouts.app')

@section('content')
     @include('components.toast')
    <div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Attendance List for Class Teacher</h2>
            </div>
          
        </div>
        
        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-600 text-white rounded-tl rounded-tr">
                <div class="w-2/12 px-4 py-3">Date</div>
                <div class="w-2/12 px-4 py-3">Class Name</div>
                <div class="w-2/12 px-4 py-3">Student Name</div>
                <div class="w-2/12 px-4 py-3">Roll No</div>
                <div class="w-2/12 px-4 py-3">Status</div>
                <div class="w-2/12 px-4 py-3">Taken By</div>
            </div>
            
        @forelse ($attendances as $attendance)
            <div class="flex flex-wrap items-center border-b text-sm font-medium text-gray-700 hover:bg-gray-50">
                <div class="w-2/12 px-4 py-3">
                    {{ \Carbon\Carbon::parse($attendance->attendence_date)->format('d M, Y') }}
                </div>
                <div class="w-2/12 px-4 py-3">
                    {{ $attendance->class->class_name ?? '—' }}
                </div>
                <div class="w-2/12 px-4 py-3">
                    {{ $attendance->student->user->name ?? '—' }}
                </div>
                <div class="w-2/12 px-4 py-3">
                    {{ $attendance->student->roll_number ?? '—' }}
                </div>
                <div class="w-2/12 px-4 py-3 ">
                    @if ($attendance->attendence_status)
                        <span class="text-green-600 font-semibold">Present</span>
                    @else
                        <span class="text-red-600 font-semibold">Absent</span>
                    @endif
                </div>
                 <div class="w-2/12 px-4 py-3">
                    {{ $attendance->teacher->user->name ?? '—' }}
                </div>
            </div>
        @empty
            <div class="p-6 text-gray-600 text-center">
                No attendance records found for your classes.
            </div>
        @endforelse
    </div>
@endsection

