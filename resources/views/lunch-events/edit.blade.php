<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Lunch Event') }}: {{ $lunchEvent->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Form menggunakan PUT method untuk update --}}
                    <form method="POST" action="{{ route('lunch-events.update', $lunchEvent->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Layout 2 Kolom untuk input pendek --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            <!-- Title (Name) -->
                            <div>
                                <x-input-label for="name" :value="__('Title')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                    :value="old('name', $lunchEvent->name)" 
                                    required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <!-- Date -->
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700">{{ __('Event Date') }}</label>
                                <input id="event_date" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('event_date') border-red-500 @enderror" type="date" name="event_date" value="{{ old('event_date', $lunchEvent->event_date) }}" required />
                                @error('event_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8"> 
                            <div class="mt-4">
                                <x-input-label for="status" :value="__('Status')" />
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror" 
                                    required>
                                    <option value="scheduled" {{ old('status', $lunchEvent->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="done" {{ old('status', $lunchEvent->status) == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                            

                            {{-- Layout 1 Kolom untuk dropdown --}}
                            <div class="mt-4">
                                <x-input-label for="restaurant_id" :value="__('Restaurant')" />
                                <select name="restaurant_id" id="restaurant_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('restaurant_id') border-red-500 @enderror" 
                                    required>
                                    <option value="">Select Restaurant</option>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}" 
                                            {{ old('restaurant_id', $lunchEvent->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('restaurant_id')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            <!-- upload file image -->
                            <div class="mt-4">
                                <x-input-label for="image" :value="__('Foto Bukti')" />
                                <input type="file" name="image" id="image" class="block mt-1 w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100"/>
                                @if($lunchEvent->image)
                                    <p class="text-sm text-gray-500 mt-2">Current Image: <a href="{{ asset('storage/' . $lunchEvent->image) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View Image</a></p>
                                @endif
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                            <!-- upload nota -->
                            
                            <div class="mt-4">
                                <x-input-label for="receipt" :value="__('Receipt')" />
                                <input type="file" name="nota" id="receipt" class="block mt-1 w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100"/>
                                @if($lunchEvent->nota)
                                    <p class="text-sm text-gray-500 mt-2">Current Receipt: <a href="{{ asset('storage/' . $lunchEvent->nota) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">View Receipt</a></p>
                                @endif
                                <x-input-error :messages="$errors->get('receipt')" class="mt-2" />
                            </div>
                        </div>    
                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            {{-- Menggunakan textarea native, karena x-text-input biasanya hanya untuk input type="text" --}}
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror" 
                                required>{{ old('description', $lunchEvent->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('lunch-events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ml-4 bg-green-500 hover:bg-green-600">
                                {{ __('Update Event') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>