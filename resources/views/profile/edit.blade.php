<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900">
                {{ __('Profile Settings') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Profile Information --}}
            <div class="p-4 sm:p-8 bg-white shadow-lg sm:rounded-lg">
                <div class="max-w-full">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow-lg sm:rounded-lg">
                <div class="max-w-full">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="p-4 sm:p-8 bg-white shadow-lg sm:rounded-lg">
                <div class="max-w-full">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
