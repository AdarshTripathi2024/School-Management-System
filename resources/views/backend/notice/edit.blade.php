@extends('layouts.app')

@section('content')
<div class="roles">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Edit Notice</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <a href="{{ route('notice.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path>
                </svg>
                <span class="ml-2 text-xs font-semibold">Back</span>
            </a>
        </div>
    </div>

    <div class="table w-full mt-8 bg-white rounded">
        <form action="{{ route('notice.update',$notice->id) }}" method="POST" class="w-full max-w-xl px-6 py-12" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Title
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="title" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ $notice->title }}">
                    @error('title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Content
                    </label>
                </div>
                <div class="md:w-2/3">
                    <textarea name="content" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text">{{ $notice->content }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Issue Date
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="notice_date" id="notice_datepicker" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value=" {{ old('notice_date', $notice->notice_date) }}">
                    @error('notice_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Expiry Date
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="expiry_date" id="expiry_datepicker" autocomplete="off" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value=" {{ old('expiry_date', $notice->expiry_date) }}"">
                    @error('expiry_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
          <div class="md:flex md:items-center mb-6">
    <div class="md:w-1/3">
        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
            Attachment
        </label>
    </div>
    <div class="md:w-2/3 space-y-3">

        {{-- File Input --}}
        <input name="attachment"
               class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4
                      text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
               type="file"
               value="{{ old('attachment') }}">
        @error('attachment')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror

        {{-- Current Attachment --}}
        @if ($notice->attachment)
            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded p-3">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-file-pdf text-red-500 text-2xl"></i>
                    <span class="text-sm text-gray-700 font-medium">
                        {{ "Current file" }}
                    </span>
                </div>
                <a href="{{ asset('storage/' . $notice->attachment) }}"
                   target="_blank"
                   class="text-blue-600 text-sm font-semibold hover:underline">
                    View File
                </a>
            </div>
        @endif
    </div>
</div>

           
            @php
            $selectedAudience = old('audience', is_array($notice->audience) ? $notice->audience : explode(',', $notice->audience));
            @endphp

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Audience
                    </label>
                </div>

                <div class="md:w-2/3 flex flex-wrap gap-4 items-center">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="audience[]" value="students"
                            class="audience-box-option h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ in_array('students', $selectedAudience) ? 'checked' : '' }}>
                        <span class="mr-2 ml-1 text-gray-700 text-sm">Students</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input type="checkbox" name="audience[]" value="teachers"
                            class="audience-box-option h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ in_array('teachers', $selectedAudience) ? 'checked' : '' }}>
                        <span class="mr-2 ml-1 text-gray-700 text-sm">Teachers</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input type="checkbox" name="audience[]" value="parents"
                            class="audience-box-option h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ in_array('parents', $selectedAudience) ? 'checked' : '' }}>
                        <span class="mr-2 ml-1 text-gray-700 text-sm">Parents</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input type="checkbox" name="audience[]" value="all" id="audience_all"
                            class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ in_array('all', $selectedAudience) ? 'checked' : '' }}>
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
                            Update Notice
                        </button>
                    </div>
                </div>
        </form>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const $allBox = $('#audience_all');
    const $otherBoxes = $('.audience-box-option'); // excludes "all" checkbox

    // Initialize state on load
    if ($allBox.is(':checked')) {
        $otherBoxes.prop('checked', true).prop('disabled', true);
    }

    // When "All" checkbox is toggled
    $allBox.on('change', function () {
        if ($(this).is(':checked')) {
            $otherBoxes.prop('checked', true).prop('disabled', true);
        } else {
            $otherBoxes.prop('disabled', false);
        }
    });

    // When any specific checkbox is unchecked, disable "All"
    $otherBoxes.on('change', function () {
        if (!$(this).is(':checked')) {
            $allBox.prop('checked', false);
            $otherBoxes.prop('disabled', false);
        }

        // If all specific boxes are checked manually, auto-check "All"
        if ($otherBoxes.filter(':checked').length === $otherBoxes.length) {
            $allBox.prop('checked', true);
            $otherBoxes.prop('disabled', true);
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