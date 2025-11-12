@if (session('success') || session('error'))
    <div id="toast"
       class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
z-50 flex items-center w-auto max-w-md p-4 text-gray-700 bg-white rounded-lg shadow-lg border 
{{ session('success') ? 'border-green-400' : 'border-red-400' }}"
 role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ session('success') ? 'text-green-500 bg-green-100' : 'text-red-500 bg-red-100' }} rounded-lg">
            @if(session('success'))
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M16.707 5.293a1 1 0 01.083 1.32l-.083.094-8 8a1 1 0 01-1.32.083l-.094-.083-4-4a1 1 0 011.32-1.497l.094.083L8 12.585l7.293-7.292a1 1 0 011.414 0z"
                          clip-rule="evenodd" />
                </svg>
            @else
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 9V5a1 1 0 012 0v4h4a1 1 0 010 2h-4v4a1 1 0 01-2 0v-4H6a1 1 0 010-2h4z"
                          clip-rule="evenodd" />
                </svg>
            @endif
        </div>
        <div class="ml-3 text-sm font-semibold">
            {{ session('success') ?? session('error') }}
        </div>
        <button type="button" id="closeToast"
            class="ml-3 text-gray-400 hover:text-gray-800 focus:outline-none"
            aria-label="Close">✕
        </button>
    </div>
@endif
