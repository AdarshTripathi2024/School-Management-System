@extends('layouts.app')

@section('content')
<div class="roles">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-gray-700 uppercase font-bold">Edit Fee Structure for Class</h2>
        </div>
        <div class="flex flex-wrap items-center">
            <a href="{{ route('fees.structure.index') }}" class="bg-gray-700 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path>
                </svg>
                <span class="ml-2 text-xs font-semibold">Back</span>
            </a>
        </div>
    </div>

    <div class="table w-full mt-8 bg-white rounded">
        <form action="{{ route('fees.structure.update', $fee_structure->id ) }}" method="POST" class="w-full max-w-xl px-6 py-12">
            @csrf
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Class<span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="class" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" value="{{$fee_structure->grade->class_name}}">
                    @error('class')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="md:flex md:items-start mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Fee Components
                    </label>
                </div>
                <div class="md:w-2/3">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($fee_structure->children as $child)
                        <div>
                            <span class="block font-semibold text-gray-700 text-sm mb-1">{{ $child->feeComponent->name }}</span>
                            <input name="component[]" type="hidden" value="{{ $child->feeComponent->id }}">
                            <input   type="number" name="amount[]" data-name="{{ $child->feeComponent->name }}" 
                            class="amount bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" value="{{ $child->amount }}">
                        </div>
                        @endforeach
                    </div>
                    @error('amount')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Total Fee<span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="total"  id="total" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="number" value="{{ $fee_structure->total_fee}}">
                    @error('total')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Monthly Installment<span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="monthly" id="monthly_input" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ $fee_structure->monthly_installment }}">
                    @error('monthly')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Quarterly Installment<span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="quarterly" id="quarterly_input" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ $fee_structure->quarterly_installment  }}">
                    @error('quarterly')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Half Yearly Installment<span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="halfyearly" id="halfyearly_input" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ $fee_structure->halfyearly_installment }}">
                    @error('halfyearly')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        Annual Installment<span class="text-red-500">*</span>
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input name="annual" id="annual_input" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" value="{{ $fee_structure->total_fee }}">
                    @error('annual')
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        function calculateTotal() {
        let total = 0;
        $('.amount').each(function() {
            let val = parseFloat($(this).val()) || 0; 
            total += val;
        });
        $('#total').val(total); 
    }


    calculateTotal();
    $('.amount').on('input', calculateTotal);
});
</script>


<script>
$(document).ready(function(){

    function calculateFees() {
        let admissionFee = 0;
        let recurringFee = 0;

        $('.amount').each(function() {
            let val = parseFloat($(this).val()) || 0;
            let name = $(this).data('name'); 

            if (name === "Admission Fee") {
                admissionFee += val;
            } else {
                recurringFee += val;
            }
        });

        let totalFee = admissionFee + recurringFee;
        $("#total").val(totalFee);
        $("#monthly_input").val((recurringFee / 12).toFixed(2));
        $("#quarterly_input").val((recurringFee / 4).toFixed(2));
        $("#halfyearly_input").val((recurringFee / 2).toFixed(2));
        $("#annual_input").val((recurringFee).toFixed(2));
    }
    calculateFees();
    $('.amount').on('input', calculateFees);

});
</script>

@endsection