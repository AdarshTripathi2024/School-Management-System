@extends('layouts.app')

@section('content')
    <div class="roles">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Add New Student</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('student.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Back</span>
                </a>
            </div>
        </div>
        
        <div class="table w-full mt-8 bg-white rounded">
            <form action="{{ route('student.store') }}" method="POST" class="w-full max-w-xl px-6 py-12" enctype="multipart/form-data">
                @csrf
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Name      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="name"  id="name" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Email      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="email" id="email" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Password      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="password" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="password" value="password">
                        @error('password')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Roll Number 
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="roll_number" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="number" value="{{ old('roll_number') }}">
                        @error('roll_number')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Phone      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="phone" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('phone') }}">
                        @error('phone')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Gender      <span class="text-red-500">*</span>
                        </label>
                    </div>
                
                    <div class="md:w-2/3">
                        <div class="flex flex-row items-center">
                            <label class="block text-gray-500 font-bold">
                                <input name="gender" class="mr-2 leading-tight" type="radio" value="male">
                                <span class="text-sm">Male</span>
                            </label>
                            <label class="ml-4 block text-gray-500 font-bold">
                                <input name="gender" class="mr-2 leading-tight" type="radio" value="female">
                                <span class="text-sm">Female</span>
                            </label>
                            <label class="ml-4 block text-gray-500 font-bold">
                                <input name="gender" class="mr-2 leading-tight" type="radio" value="other">
                                <span class="text-sm">Other</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Date of Birth      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="dateofbirth" id="datepicker-sc" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('dateofbirth') }}">
                        @error('dateofbirth')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Current Address      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="current_address"  id="current_address" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('current_address') }}">
                        @error('current_address')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Permanent Address      <span class="text-red-500">*</span>
                        </label>
                    </div>

                    <div class="md:w-2/3">
                        <input id="permanent_address" name="permanent_address"
                            class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            type="text" value="{{ old('permanent_address') }}">

                        @error('permanent_address')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror

                        <!-- ✅ Add checkbox here -->
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="sameAddress" class="form-checkbox text-blue-600">
                                <span class="ml-2 text-gray-600 text-sm">Same as Current Address</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Assign Class      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3 block text-gray-600 font-bold">
                        <div class="relative">
                            <select name="class_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                                <option value="">--Select Class--</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Student's Parent      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3 block text-gray-600 font-bold">
                        <div class="relative">
                            <select name="parent_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                                <option value="">--Select Parent--</option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->user->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Picture :
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="profile_picture" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="file">
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Require School Transport
                        </label>
                    </div>
                    <div class="md:w-2/3 flex items-center space-x-2">
                        <input 
                            type="checkbox" 
                            name="require_transport" 
                            id="require_transport" 
                            value="1"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            @checked(old('require_transport', $student->require_transport ?? false)) >
                        <label for="require_transport" class="text-gray-700"> &ensp; Yes</label>
                    </div>
                </div>
                
                <div class="md:flex md:items-center mb-6" id="transportDropdown" style="display: none;">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Assign Transport
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <select name="transport_id" 
                                class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 focus:outline-none focus:bg-white focus:border-blue-500">
                            <option value="">-- Select Transport --</option>
                            @foreach($transports as $transport)
                                <option value="{{ $transport->id }}" @selected(old('transport_id') == $transport->id)>
                                  {{ strtoupper($transport->vehicle_type ) }}  {{ $transport->vehicle_number }} - {{ $transport->route }}
                                </option>
                            @endforeach
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
        $( "#datepicker-sc" ).datepicker({ dateFormat: 'yy-mm-dd' });
    })
</script>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#sameAddress').on('change', function() {
        if ($(this).is(':checked')) {
            $('#permanent_address').val($('#current_address').val());
        } else {
            $('#permanent_address').val('');
        }
    });

    $('#name').on('blur', function() {
    const name = $(this).val().trim().toLowerCase().replace(/\s+/g, '');
    $('#email').val(name + '@gmail.com');
});

  if ($('#require_transport').is(':checked')) {
            $('#transportDropdown').show();
        }

    // Toggle dropdown on checkbox change
    $('#require_transport').on('change', function() {
        if ($(this).is(':checked')) {
            $('#transportDropdown').slideDown();  // smooth animation
        } else {
            $('#transportDropdown').slideUp();
            $('select[name="transport_id"]').val(''); // clear selection
        }
    });

});
</script>
@endpush
