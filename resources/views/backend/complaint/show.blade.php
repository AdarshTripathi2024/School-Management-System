@extends('layouts.app')

@section('content')
    <div class="roles">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Complaint Detail</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('complaint.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Back</span>
                </a>
            </div>
        </div>
       <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4">Complaint Information</h3>

        <div class="grid grid-cols-2 gap-4">
            <div><strong>Status:</strong> <span class="capitalize">{{ $complaint->status }}</span></div>
            <div><strong>From:</strong> {{ $complaint->fromUser->name }}</div>
            <div><strong>To:</strong> {{ $complaint->toUser->name }}</div>
            <div class="col-span-2"><strong>Subject:</strong> {{ $complaint->subject }}</div>
            <div class="col-span-2"><strong>Description:</strong> {{ $complaint->description }}</div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h3 class="text-lg font-semibold mb-4">Complaint Logs</h3>
        @if($complaint_log->isEmpty())
            <p class="text-gray-600">No activity yet.</p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach($complaint_log as $log)
                    <li class="py-3">
                        <div class="flex justify-between">
                            <span>{{ $log->remark }}</span>
                            <small class="text-gray-500">{{ $log->created_at }}</small>
                        </div>
                        <p class="text-sm text-gray-600">Status: <strong>{{ $log->status }}</strong></p>
                        <p class="text-sm text-gray-600">Status Changed By: <strong>{{ $log->changed_by_name }}</strong></p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection