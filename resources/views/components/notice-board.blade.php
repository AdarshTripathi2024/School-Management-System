{{-- 🔔 NOTICE BOARD SECTION --}}
<div class="w-full bg-white shadow-md rounded-lg p-4 mb-8 mx-auto relative overflow-hidden">
    <h1 class="text-xl font-bold text-gray-800 border-b pb-2 mb-3 flex items-center justify-between">
        Notice Board
    </h1>

    {{-- Notice list container --}}
    <div id="notice-board" class="h-48 overflow-hidden relative">
        <ul class="absolute w-full">
            @forelse($notices as $notice)
                <li class="py-3 border-b border-gray-200 bg-gray-200 px-3 mb-2 rounded">
                    
                    {{-- 1️⃣ HEADER: Title + Date --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-gray-800">{{ $notice->title }}</span>
                            <span class="text-xs text-gray-500 ml-2">
                                ({{ \Carbon\Carbon::parse($notice->notice_date)->format('d M Y') }})
                            </span>
                        </div>  
                    </div>

                    {{-- 2️⃣ BODY: Content --}}
                    <p class="mt-2 text-gray-700 text-sm leading-snug">
                        {{ $notice->content }}
                    </p>

                    {{-- 3️⃣ FOOTER: Attachment --}}
                    @if ($notice->attachment)
                        <div class="mt-2 text-right">
                            <a href="{{ Storage::url($notice->attachment) }}" target="_blank"
                               class="inline-flex items-center text-blue-600 text-sm font-semibold hover:underline">
                                <i class="fa-solid fa-folder text-blue-500 mr-2"></i> View File
                            </a>
                        </div>
                    @endif
                </li>
            @empty
                <li class="text-gray-500 text-center py-6">No active notices.</li>
            @endforelse
        </ul>
    </div>


    {{-- Show All link --}}
    <div class="text-center mt-4 border-b border-gray-200">
        <a href="{{ route('notice.index') }}" class="text-blue-600 font-semibold hover:underline">
            Show All →
        </a>
    </div>
</div>
@if($notices->count() > 1)
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const $board = $('#notice-board ul');
    const $items = $board.find('li');
    let index = 0;

    // Position all notices vertically stacked
    $items.each(function (i) {
        $(this).css({
            position: 'absolute',
            top: (i === 0 ? 0 : '100%'),
            width: '100%',
            opacity: (i === 0 ? 1 : 0),
            transition: 'all 0.8s ease-in-out'
        });
    });

    function showNextNotice() {
        const $current = $items.eq(index);
        index = (index + 1) % $items.length;
        const $next = $items.eq(index);

        // Animate: current slides up, next fades in from below
        $current.css({ top: '-100%', opacity: 0 });
        $next.css({ top: 0, opacity: 1 });

        // Reset current after animation (so it can come back later)
        setTimeout(() => {
            $current.css({ top: '100%' });
        }, 900);
    }

    if ($items.length > 1) {
        setInterval(showNextNotice, 3000); // 3 seconds per notice
    }
});
</script>
@endif
