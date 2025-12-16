@extends('layouts.app')
@section('content')
@include('components.toast')
<div class="roles-permissions">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Fee Management</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <a href="{{ route('feeDeposit.create') }}" class="bg-gray-200 text-gray-700 text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Deposit Fees</span>
                </a>
        </div>
    </div>

    <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
        <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-600 text-white rounded-tl rounded-tr">
            <div class="w-1/12 px-4 py-3">#</div>
            <div class="w-2/12 px-4 py-3">Student</div>
            <div class="w-1/12 px-4 py-3">Class</div>
            <div class="w-2/12 px-4 py-3">Paid Amount</div>
            <div class="w-2/12 px-4 py-3">Months</div>
            <div class="w-2/12 px-4 py-3">Payment Date</div>
            <div class="w-2/12 px-4 py-3 text-right">Action</div>
        </div>
        @foreach ($deposits as $deposit)
        <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
            <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight"></div>
            <div class="w-7/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight"></div>

            <div class="w-2/12 flex justify-end px-3">
                <form action="" method="POST" onsubmit="return confirm('Delete this item?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        <i class="fa fa-trash text-red-600"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection