@extends('layouts.app')

@section('content')
    <div class="roles">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Add New Complain</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('complaint.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Back</span>
                </a>
            </div>
        </div>
        
        <div class="table w-full mt-8 bg-white rounded">
            <form action="{{ route('complaint.store') }}" method="POST" class="w-full max-w-xl px-6 py-12" enctype="multipart/form-data"    >
                @csrf
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Complaint Subject (Topic)     <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="subject" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ old('subject') }}">
                        @error('subject')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Complaint Description      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <textarea name="description" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
              
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                           Send To      <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <select name="complaint_to_id" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <option value="">-- Select --</option>
                            @foreach($toUser as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('complaint_to_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Select Student
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <select name="student_id" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <option value="">-- Select --</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                           </select>
                        @error('complaint_from_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror 
                    </div>
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                            Send Related Document
                        </label>
                    </div>
                    <div class="md:w-2/3">
                        <input name="attachment" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"  type="file" >
                        @error('attachment')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
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
@endsection