@extends('layouts.app')

@section('content')
    <div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Fee Management</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('fees.structure.create') }}" class="bg-green-500 text-white text-sm uppercase py-2 px-4 mr-2 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14" fee="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Create Fee Structure</span>
                </a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-600 text-white rounded-tl rounded-tr">
                <div class="w-1/12 px-4 py-3">#</div>
                <div class="w-1/12 px-4 py-3">Class</div>
                <div class="w-4/12 px-4 py-3">Fees Components</div>
                <div class="w-1/12 px-4 py-3">Total Fee</div>
                <div class="w-1/12 px-4 py-3">Monthly</div>
                <div class="w-1/12 px-4 py-3">Quarterly</div>
                <div class="w-1/12 px-4 py-3">Half Yearly</div>
                <div class="w-2/12 px-4 py-3 text-right">Action</div>
            </div>
            @foreach ($structures as $structure)
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $structure->id }}</div>
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $structure->grade->class_name }}</div>
                    <div class="w-4/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">@foreach($structure->children as $child)
                                                                                                        {{ $child->feeComponent->name }} - Rs. {{ $child->amount }}<br>
                                                                                                    @endforeach</div>
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">Rs. {{ $structure->total_fee }}</div>
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">Rs. {{ $structure->monthly_installment }}</div>
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">Rs. {{ $structure->quarterly_installment }}</div>
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">Rs. {{ $structure->halfyearly_installment }}</div>
                    <div class="w-2/12 flex justify-end px-3">
                        <a href="{{ route('fees.structure.edit', $structure->id) }}">
                            <i class="fas fa-pencil"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
@endsection