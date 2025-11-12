@extends('layouts.app')

@section('content')
     @include('components.toast')
    <div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Upcoming Holidays</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('holiday.create') }}" class="bg-green-500 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Add New Holidays</span>
                </a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-600 text-white rounded-tl rounded-tr">
                <div class="w-3/12 px-4 py-3">Date</div>
                <div class="w-3/12 px-4 py-3">Occasion</div>
                <div class="w-2/12 px-4 py-3">Applies to Teacher</div>
                <div class="w-2/12 px-4 py-3">Remark</div>
                <div class="w-2/12 px-4 py-3 text-right">Actions</div>
            </div>
            @foreach ($holidays as $holiday)
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $holiday->date }}</div>
                    <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $holiday->occasion }}</div>
                    <div class="w-2/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">@if($holiday->is_for_teacher == 1){{ 'Yes' }}@else{{ 'No' }} @endif
                    </div>
                    <div class="w-2/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">{{ $holiday->remark ?? '-' }}</div>
                    <div class="w-2/12 flex items-center justify-end px-3">
                        <a href="{{ route('holiday.edit',$holiday->id) }}">
                            <svg class="h-6 w-6 fill-current text-green-600" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pen-square" class="svg-inline--fa fa-pen-square fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 480H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48v352c0 26.5-21.5 48-48 48zM238.1 177.9L102.4 313.6l-6.3 57.1c-.8 7.6 5.6 14.1 13.3 13.3l57.1-6.3L302.2 242c2.3-2.3 2.3-6.1 0-8.5L246.7 178c-2.5-2.4-6.3-2.4-8.6-.1zM345 165.1L314.9 135c-9.4-9.4-24.6-9.4-33.9 0l-23.1 23.1c-2.3 2.3-2.3 6.1 0 8.5l55.5 55.5c2.3 2.3 6.1 2.3 8.5 0L345 199c9.3-9.3 9.3-24.5 0-33.9z"></path></svg>
                        </a>
                        <a href="{{ route('holiday.destroy', $holiday->id) }}" data-url="{{ route('holiday.destroy', $holiday->id) }}" class="deletebtn ml-1 bg-red-600 block p-1 border border-red-600 rounded-sm">
                            <svg class="h-3 w-3 fill-current text-gray-100" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" class="svg-inline--fa fa-trash fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{-- $holidays->links() --}}
        </div>
        @include('backend.modals.delete',['name' => 'holiday'])



<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm8 3V3H6v2h8z" />
        </svg>
        Academic Calendar
    </h3>

    <div class="bg-gradient-to-br from-blue-50 to-white p-4 rounded-2xl shadow-md border border-gray-100 transition-transform duration-200 hover:scale-[1.01]">
        <div id="calendar"></div>
    </div>
</div>

<!-- Include FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
    /*  Calendar Modern Styling */
    #calendar {
        max-width: 900px;
        margin: 0 auto;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
    }

    .fc .fc-toolbar-title {
        font-size: 1.2rem;
        color: #1e293b;
        font-weight: 600;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 1rem;
    }

    .fc .fc-button {
        background: linear-gradient(to right, #2563eb, #3b82f6);
        border: none;
        color: white;
        font-size: 0.8rem;
        border-radius: 0.5rem;
        padding: 0.4rem 0.8rem;
        transition: all 0.2s;
    }

    .fc .fc-button:hover {
        background: linear-gradient(to right, #1d4ed8, #2563eb);
    }

    .fc-daygrid-day-number {
        font-weight: 500;
        color: #334155;
    }

    /* Sunday Highlight */
    .fc-day-sun {
        background-color: #fee2e2 !important;
    }

    /* Holidays Highlight */
    .fc-event {
        border: none !important;
        border-radius: 6px !important;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .fc-daygrid-event-dot {
        display: none;
    }

    /* Smooth hover */
    .fc-daygrid-day:hover {
        background-color: #f1f5f9;
        cursor: pointer;
        transition: background 0.3s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 550,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: [
            @foreach($allHolidays as $holiday)
            {
                title: '{{ $holiday->occasion }}',
                start: '{{ $holiday->date }}',
                color: '#22c55e', // Tailwind green-500
                textColor: '#fff'
            },
            @endforeach
        ],
        dayCellDidMount: function(info) {
            if (info.date.getDay() === 0) {
                info.el.style.backgroundColor = '#fee2e2'; // Sunday red-100
            }
        },
    });

    calendar.render();
});
</script>

</div>
@endsection

@push('scripts')
<script>
    $(function() {
        $( ".deletebtn" ).on( "click", function(event) {
            event.preventDefault();
            $( "#deletemodal" ).toggleClass( "hidden" );
            var url = $(this).attr('data-url');
            $(".remove-record").attr("action", url);
        })        
        
        $( "#deletemodelclose" ).on( "click", function(event) {
            event.preventDefault();
            $( "#deletemodal" ).toggleClass( "hidden" );
        })
    })
</script>
@endpush

