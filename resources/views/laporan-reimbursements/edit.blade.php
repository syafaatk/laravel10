<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan Reimbursement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.laporan-reimbursements.update', $laporanReimbursement->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $laporanReimbursement->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <!-- Start Date -->
                        <div class="mt-4">
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $laporanReimbursement->start_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mt-4">
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $laporanReimbursement->end_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount', $laporanReimbursement->amount)" required step="0.01" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="pending" {{ old('status', $laporanReimbursement->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $laporanReimbursement->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="done" {{ old('status', $laporanReimbursement->status) == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="rejected" {{ old('status', $laporanReimbursement->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Attachment -->
                        <div class="mt-4">
                            <x-input-label for="attachment" :value="__('Attachment (PDF)')" />
                            <input id="attachment" class="block mt-1 w-full" type="file" name="attachment" />
                            @if ($laporanReimbursement->attachment)
                                <p class="text-sm text-gray-600 mt-2">Current file: <a href="{{ \Storage::url($laporanReimbursement->attachment) }}" target="_blank" class="text-blue-600 hover:underline">View Attachment</a></p>
                            @endif
                            <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                        </div>
                        

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>