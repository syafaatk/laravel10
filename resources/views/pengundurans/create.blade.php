<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Resignation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                
                {{-- LEFT: Resignation Application Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Apply for New Resignation</h3>
                            
                            <form action="{{ route('pengunduran.store') }}" method="POST" id="pengunduranForm">
                                @csrf

                                {{-- User Selection (Admin only) --}}
                                @if(Auth::user()->hasRole('admin'))
                                    <div class="mb-4">
                                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                                        <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="updateUserRemainingCuti()">
                                            <option value="">-- Select User --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" data-cuti='{{ json_encode($usersRemainingCuti[$user->id] ?? []) }}'>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <p class="text-sm text-blue-900"><strong>Applying as:</strong> {{ Auth::user()->name }}</p>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label for="pic" class="form-label">Pilih Approver</label>
                                    <select class="form-control" id="pic" name="pic" required>
                                        <option value="">Pilih PIC</option>
                                        <option value="9520131577">FITHRI HALIM AHMAD</option>
                                        <option value="8913230864">DEDEK APRIYANI</option>
                                        <option value="8916131158">ARYA REZA NUGRAHA</option>
                                        <option value="8520131736">ASEP MARYANA</option>
                                        <option value="9824132111">ZULFIKAR MURAKABIMAN</option>
                                    </select>
                                </div>

                                {{-- Resignation Reason --}}
                                <div class="mb-4">
                                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Resignation</label>
                                    <textarea name="reason" id="reason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('reason') border-red-500 @enderror" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Request Date --}}
                                <div class="mb-4">
                                    <label for="requested_date" class="block text-sm font-medium text-gray-700 mb-2">Request Date</label>
                                    <input type="date" name="requested_date" id="requested_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('requested_date') border-red-500 @enderror" value="{{ old('requested_date') }}" required>
                                    @error('requested_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex gap-3">
                                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                                        {{ __('Submit Resignation') }}
                                    </button>
                                    <a href="{{ route('cuti.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>