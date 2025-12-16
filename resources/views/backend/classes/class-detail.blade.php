@extends('layouts.app')

@section('content')
 @include('components.toast')
<div class="roles">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Class '{{$detail->class_name }}' Details</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <a href="{{ route('classes.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path>
                </svg>
                <span class="ml-2 text-xs font-semibold">Back</span>
            </a>
        </div>
    </div>
    <div class="w-full py-8">
        <div class="flex items-center bg-gray-600">
            <div class="w-1/2 text-left text-white py-2 px-4 font-semibold">Class Teacher</div>
            <div class="w-1/2 text-right text-white py-2 px-4 font-semibold">Action</div>
        </div>

        <div class="flex items-center justify-between border border-gray-200 hover:bg-gray-50 transition">
            <!-- Teacher Name -->
            <div class="w-1/2 text-left text-gray-700 py-2 px-4 font-medium">
              {{ $detail->teacher?->user?->name ?? 'Not Assigned' }}
            </div>

            <!-- Action Button -->
            <div class="w-1/2 text-right py-2 px-4">
                <button id="openChangeModal" class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition"
                    title="Change Class Teacher">
                    <i class="fa-solid fa-user-pen"></i> Change
                </button>
            </div>
        </div>
    </div>

    <div class="w-full py-12" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <h2 class="text-gray-700 uppercase font-bold my-2">Class Subjects</h2>
            
            <button id="openModal"
                class="bg-blue-600 text-white px-4 py-2 my-1 rounded shadow hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus mr-1"></i> Add Subject
            </button>

        </div>
        <div class="flex items-center bg-gray-600">
            <div class="w-1/4 text-left text-white py-2 px-4 font-semibold">Name</div>
            <div class="w-1/4 text-left text-white py-2 px-4 font-semibold">Subject Code</div>
            <div class="w-1/4 text-right text-white py-2 px-4 font-semibold">Subject Teacher</div>
            <div class="w-1/4 text-right text-white py-2 px-4 font-semibold">Action</div>
        </div>
        @foreach($subjects as $subject)
        <div class="flex items-center justify-between border border-gray-200 mb-px hover:bg-gray-50 transition">
            <div class="w-1/4 text-left text-gray-700 py-2 px-4 font-medium">
                {{ $subject->subject->name }}
            </div>
            <div class="w-1/4 text-left text-gray-700 py-2 px-4 font-medium">
                {{ $subject->subject->subject_code }}
            </div>
            <div class="w-1/4 text-right text-gray-700 py-2 px-4 font-medium">
                {{ $subject->teacher?->user?->name ?? 'Not Assigned' }}
            </div>
            <div class="w-1/4 text-right text-gray-700 py-2 px-4 space-x-2">
               <button class="openChangeTeacherModal inline-block bg-yellow-500 text-white p-1.5 rounded hover:bg-yellow-600"
                    title="Change Subject Teacher" data-id="{{ $subject->id }}" >
                    <i class="fa-solid fa-pen text-xs p-2"></i>
                </button>
                <form action="{{ route('remove.subject.from.class',  ['class_id' => $detail->id, 'subject_id' => $subject->id]) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white p-1.5 rounded hover:bg-red-600"
                        title="Remove Subject"
                        onclick="return confirm('Are you sure you want to remove this subject from Class {{ $detail->class_name }} ?')">
                        <i class="fa-solid fa-trash text-xs p-2"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>


    <div class="w-full py-12">
        <h2 class="text-gray-700 uppercase font-bold my-2">Class '{{$detail->class_numeric}}' Students</h2>
        <div class="flex items-center bg-gray-600">
            <div class="w-1/4 text-left text-white py-2 px-4 font-semibold">Name</div>
            <div class="w-1/4 text-left text-white py-2 px-4 font-semibold">Email</div>
            <div class="w-1/4 text-right text-white py-2 px-4 font-semibold">Phone</div>
            <div class="w-1/4 text-right text-white py-2 px-4 font-semibold">Parents</div>
        </div>
    @foreach($detail->students as $student)
        <div class="flex items-center justify-between border border-gray-200 mb-px">
            <div class="w-1/4 text-left text-gray-600 py-2 px-4 font-medium">{{ $student->user->name }}</div>
            <div class="w-1/4 text-left text-gray-600 py-2 px-4 font-medium">{{ $student->user->email}}</div>
            <div class="w-1/4 text-right text-gray-600 py-2 px-4 font-medium">{{ $student->phone }}</div>
            <div class="w-1/4 text-right text-gray-600 py-2 px-4 font-medium">{{ $student->parent->user->name }}</div>
        </div>
    @endforeach

    </div>

<!-- Modal -->
<div id="classModal"
     class="fixed inset-0 flex items-center justify-center z-50"
     style="display: none; background-color: rgba(255, 255, 255, 0.5);">

    <!-- Modal Box -->
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <!-- Close Button -->
        <button id="closeModal"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
            ✕
        </button>

        <!-- Modal Heading -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Add Subject to Class</h3>

        <form action="{{ route('store.class.assign.subject') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input class="hidden" value="{{ $detail->id }}" name="class_id">
                <label class="block text-gray-700 font-medium mb-1">Select Subject</label>
                <select name="subject_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Select Subject --</option>
                    @foreach( $allSubjects as $subject)
                        <option value="{{$subject->id}}">{{$subject->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Assign Teacher</label>
                <select name="teacher_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Select Teacher --</option>
                    @foreach( $allTeachers as $teacher)
                        <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelModal"
                    class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>

<!--  change class teacher Modal -->
    
    <!-- Modal -->
    <div id="classChangeModal"
        class="fixed inset-0 flex items-center justify-center z-50"
        style="display: none; background-color: rgba(255, 255, 255, 0.5);">

        <!-- Modal Box -->
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <!-- Close Button -->
            <button id="closeChangeModal"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
                ✕
            </button>

        <!-- Modal Heading -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Add/Change Class Teacher</h3>

        <form action="{{ route('store.class.teacher') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input class="hidden" value="{{ $detail->id }}" name="class_id">
                <label class="block text-gray-700 font-medium mb-1">Assign Teacher</label>
                <select name="teacher_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Select Teacher --</option>
                    @foreach( $allTeachers as $teacher)
                        <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelChangeModal"
                    class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>


<!-- Changge Subject Teacher Modal -->
<!-- Modal -->
<div id="changeSubjectTeacherModal"
     class="fixed inset-0 flex items-center justify-center z-50"
     style="display: none; background-color: rgba(255, 255, 255, 0.5);">

    <!-- Modal Box -->
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <!-- Close Button -->
        <button id="closeChangeTeacherModal"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
            ✕
        </button>

        <!-- Modal Heading -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Change Subject Teacher</h3>

        <form action="{{ route('change.subject.teacher') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input class="hidden" value="{{ $detail->id }}" name="class_id">
                  <input type="hidden" name="grade_subject_teacher_id" id="subject_id_input">
                <label class="block text-gray-700 font-medium mb-1">Assign Teacher to Subject</label>
                <select name="teacher_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Select Teacher --</option>
                    @foreach( $allTeachers as $teacher)
                        <option value="{{$teacher->id}}">{{$teacher->user->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelChangeTeacherModal"
                    class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>




</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#openModal').on('click', function() {
        $('#classModal').fadeIn();
    });

    $('#closeModal, #cancelModal').on('click', function() {
        $('#classModal').fadeOut();
    });

    // Optional: Close when clicking outside the modal
    $(window).on('click', function(e) {
        if ($(e.target).is('#classModal')) {
            $('#classModal').fadeOut();
        }
    });


    $('#openChangeModal').on('click', function() {
        $('#classChangeModal').fadeIn();
    });

    $('#closeChangeModal, #cancelChangeModal').on('click', function() {
        $('#classChangeModal').fadeOut();
    });

    // Optional: Close when clicking outside the modal
    $(window).on('click', function(e) {
        if ($(e.target).is('#classChangeModal')) {
            $('#classChangeModal').fadeOut();
        }
    });

$(document).on('click', '.openChangeTeacherModal', function() {
        const subjectId = $(this).data('id');
        $('#subject_id_input').val(subjectId);
        $('#changeSubjectTeacherModal').fadeIn();
    });

    $('#closeChangeTeacherModal, #cancelChangeTeacherModal').on('click', function() {
        $('#changeSubjectTeacherModal').fadeOut();
    });

    // Optional: Close when clicking outside the modal
    $(window).on('click', function(e) {
        if ($(e.target).is('#changeSubjectTeacherModal')) {
            $('#changeSubjectTeacherModal').fadeOut();
        }
    });


});
</script>

@endsection