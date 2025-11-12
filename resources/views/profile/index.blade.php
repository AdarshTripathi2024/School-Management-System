@extends('layouts.app')

@section('content')
    @include('components.toast')
    <div class="profile">
       <div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h2 class="text-gray-700 uppercase font-bold">Profile</h2>
    </div>

    <div class="flex flex-wrap items-center space-x-3">
        <!-- Change Password Button -->
        <button 
            id="openChangePasswordModal"
            type="button"
            class="bg-yellow-600 text-white text-sm uppercase py-2 px-4 flex items-center rounded hover:bg-yellow-700 transition mr-2">
            <svg class="w-3 h-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M400 192h-24V128C376 57.31 318.7 0 248 0S120 57.31 120 128v64H96c-26.51 0-48 21.49-48 48v224c0 26.51 21.49 48 48 48h304c26.51 0 48-21.49 48-48V240C448 213.5 426.5 192 400 192zM168 128c0-44.11 35.89-80 80-80s80 35.89 80 80v64H168V128z"/>
            </svg>
            <span class="ml-2 text-xs font-semibold">Change Password</span>
        </button>

        <!-- Edit Profile Button -->
        <a href="{{ route('profile.edit') }}" 
           class="bg-blue-600 text-white text-sm uppercase py-2 px-4 flex items-center rounded hover:bg-blue-700 transition">
            <svg class="w-3 h-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"/>
            </svg>
            <span class="ml-2 text-xs font-semibold">Edit Profile</span>
        </a>
    </div>
</div>

        <div class="mt-8 bg-white rounded">
            <div class="w-full max-w-2xl mx-auto px-6 py-12 flex justify-between">
                <div>
                    <div class="md:flex md:items-center mb-4">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4 whitespace-nowrap">
                                Name
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <span class="block text-gray-600 font-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-4">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                Email :
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <span class="text-gray-600 font-bold">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <div class="md:flex md:items-center mb-4">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                Role :
                            </label>
                        </div>
                        <div class="md:w-2/3">
                            <span class="text-gray-600 font-bold">{{ auth()->user()->roles[0]->name ?? '' }}</span>
                        </div>
                    </div>
                </div>        
                <div>
                    <div>
                        <img class="w-20 h-20 sm:w-32 sm:h-32 rounded" src="{{ asset('images/profile/' . auth()->user()->profile_picture) }}" alt="avatar">    
                    </div>        
                </div>        
            </div>        
        </div>
   






<!-- Changge Password Modal -->
<!-- Modal -->
<div id="changePasswordModal"
     class="fixed inset-0 flex items-center justify-center z-50"
     style="display: none; background-color: rgba(255, 255, 255, 0.5);">

    <!-- Modal Box -->
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <!-- Close Button -->
        <button id="closeChangePasswordModal"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
            ✕
        </button>

        <!-- Modal Heading -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-center">Change Password</h3>

        <form action="{{ route('profile.changePassword') }}" method="POST">
            @csrf
            <p class="text-red-600 font-semibold my-3">Alert: User will be logged out after the password change. Be careful while changing the Password and Please Remember your new Password or note it down somewhere !! </p>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Your Current Password</label>
                <input type="password" name="current_password" class="w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Your New Password</label>
                <input type="password" name="new_password" class="w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Confirm Your New Password</label>
                <input type="password" name="new_password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelChangePasswordModal"
                    class="px-4 py-2 border border-gray-400 rounded hover:bg-gray-100 mr-2">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>


 </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    // Open modal
    $('#openChangePasswordModal').on('click', function () {
        $('#changePasswordModal').fadeIn();
    });

    // Close modal
    $('#closeChangePasswordModal, #cancelChangePasswordModal').on('click', function () {
        $('#changePasswordModal').fadeOut();
    });

    // Optional: close modal when clicking outside content
    $(window).on('click', function(e) {
        if ($(e.target).is('#changePasswordModal')) {
            $('#changePasswordModal').fadeOut();
        }
    });
});
</script>

@endsection