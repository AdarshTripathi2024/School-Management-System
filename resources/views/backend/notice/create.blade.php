@extends('layouts.app')

@section('content')
    <div class="roles">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Add New Notice</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('notice.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Back</span>
                </a>
            </div>
        </div>
        
        <div class="table w-full mt-8 bg-white rounded">
            <form action="{{ route('notice.store') }}" method="POST" class="w-full max-w-xl px-6 py-12" enctype="multipart/form-data">
                @csrf
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                           Notice Title     <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="title" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('title') }}">
                        @error('title')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                           Content      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <textarea name="content" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="">{{ old('occasion') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                           Issue Date     <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="notice_date"  id="notice_datepicker" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('notice_date') }}">
                        @error('notice_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                           Expiry Date     <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="expiry_date"   id="expiry_datepicker" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('expiry _date') }}">
                        @error('expiry_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                  <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                           Attachment     <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="attachment" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="file" value="{{ old('attachment') }}">
                        @error('attachment')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
              
               <div class="md:flex md:items-center mb-6">
    <div class="md:w-1/3">
        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
            Audience     <span class="text-red-500">*</span>
        </label>
    </div>

    <div class="md:w-2/3 flex flex-wrap gap-4 items-center">
        <label class="inline-flex items-center">
            <input type="checkbox" name="audience[]" value="students"
                class="audience-box h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                {{ is_array(old('audience')) && in_array('students', old('audience')) ? 'checked' : '' }}>
            <span class="mr-2 ml-1 text-gray-700 text-sm">Students</span>
        </label>

        <label class="inline-flex items-center">
            <input type="checkbox" name="audience[]" value="teachers"
                class="audience-box h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                {{ is_array(old('audience')) && in_array('teachers', old('audience')) ? 'checked' : '' }}>
            <span class="mr-2 ml-1 text-gray-700 text-sm">Teachers</span>
        </label>

        <label class="inline-flex items-center">
            <input type="checkbox" name="audience[]" value="parents"
                class="audience-box h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                {{ is_array(old('audience')) && in_array('parents', old('audience')) ? 'checked' : '' }}>
            <span class="mr-2 ml-1 text-gray-700 text-sm">Parents</span>
        </label>

        <label class="inline-flex items-center">
            <input type="checkbox" id="audience_all" name="audience[]" value="all"
                class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                {{ is_array(old('audience')) && in_array('all', old('audience')) ? 'checked' : '' }}>
            <span class="mr-2 ml-1 text-gray-700 text-sm font-semibold">All</span>
        </label>

        @error('audience')
            <p class="text-red-500 text-xs italic mt-2 w-full">{{ $message }}</p>
        @enderror
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function() {
    const $allBox = $('#audience_all');
    const $otherBoxes = $('.audience-box');

    // When "All" checkbox changes
    $allBox.on('change', function() {
        if ($(this).is(':checked')) {
            $otherBoxes.prop('checked', true).prop('disabled', true);
        } else {
            $otherBoxes.prop('disabled', false);
            $otherBoxes.prop('checked', false)
        }
    });

    // If user unchecks any box manually, uncheck "All"
    $otherBoxes.on('change', function() {
        if (!$(this).is(':checked')) {
            $allBox.prop('checked', false);
            $otherBoxes.prop('disabled', false);
        }
    });
});
</script>

@endsection

@push('scripts')
<script>
$(function() {       
    $("#notice_datepicker, #expiry_datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
</script>
@endpush


