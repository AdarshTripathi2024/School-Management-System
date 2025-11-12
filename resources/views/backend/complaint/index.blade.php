@extends('layouts.app')

@section('content')
    @include('components.toast')
    <div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Complaints</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('complaint.create') }}" class="bg-green-500 text-white text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Add New Complaint</span>
                </a>
            </div>
        </div>
      {{-- Responsive table wrapper --}}
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full border border-gray-300 text-sm text-left text-gray-700">
                <thead class="bg-gray-600 text-white uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">From</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Attachment</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Sent To</th>
                        <th class="px-4 py-3">Student</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Solution</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $complaint->fromUser->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $complaint->subject }}</td>
                            <td class="px-4 py-3">
                                @if ($complaint->attachment)
                                    <a href="{{ Storage::url($complaint->attachment) }}" target="_blank"
                                       class="text-blue-600 hover:underline">
                                        <i class="fa-solid fa-folder-open fa-lg"></i>
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $complaint->description ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $complaint->toUser->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $complaint->student->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $complaint->status === 'resolved' ? 'bg-green-200 text-green-800' :
                                       ($complaint->status === 'pending' ? 'bg-yellow-200 text-yellow-800' :
                                       'bg-gray-200 text-gray-700') }}">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $complaint->solution ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('complaint.edit', $complaint->id) }}" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-green-600"></i>
                                    </a>
                                    <form action="{{ route('complaint.destroy', $complaint->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete">
                                            <i class="fa-solid fa-trash text-red-600"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('complaint.show', $complaint->id) }}" title="View Detail">
                                        <i class="fa-solid fa-eye text-blue-600"></i>
                                    </a> &ensp;
                                        <button type="button" class="changeStatusBtn text-blue-600" data-id="{{ $complaint->id }}" data-status="{{ $complaint->status }}" title="change status">
                                            <i class="fa fa-refresh"></i> 
                                        </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">No complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $complaints->links() }}
        </div>
    </div>


<!-- Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
    <h2 class="text-lg font-semibold mb-4 bg-black text-white p-3">Change Complaint Status</h2>

    <form id="statusForm" method="POST">
      @csrf
      <input type="hidden" name="complaint_id" id="complaint_id">
      <div class="mb-4">
        <label for="status" class="block text-gray-700 font-medium mb-1">Select Status</label>
        <select name="status" id="status" class="w-full border rounded p-2">
            <option value="">-- Choose Status --</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="resolved">Closed</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="remark" class="block text-gray-700 font-medium mb-1">Remark</label>
        <input type="text" name="remark" id="remark" class="w-full border rounded p-2" placeholder="Enter action or remark..." required>
      </div>

      <div class="flex justify-end">
        <button type="button" id="closeModal" class="bg-gray-300 px-3 py-1 rounded mr-2">Cancel</button>
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    // open modal and set complaint id
    $('.changeStatusBtn').on('click', function() {
        const complaintId = $(this).data('id');
        const currentStatus = $(this).data('status');
        $('#complaint_id').val(complaintId);
     $('#statusForm').attr('action', '/change-complaint-status/' + complaintId);    
         // Preselect the current status
        $('#status').val(currentStatus);
        $('#statusModal').removeClass('hidden');
    });

    // close modal
    $('#closeModal').on('click', function() {
        $('#statusModal').addClass('hidden');
    });
});
</script>
@endpush

