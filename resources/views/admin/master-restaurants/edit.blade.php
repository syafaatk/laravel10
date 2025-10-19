<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Restaurant') }}: {{ $masterRestaurant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Form untuk mengupdate restoran --}}
                    <form action="{{ route('master-restaurants.update', $masterRestaurant->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Penting: Menentukan metode HTTP sebagai PUT untuk update --}}

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                            <input type="text" name="name" id="name" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror" 
                                value="{{ old('name', $masterRestaurant->name) }}" required>
                            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('address') border-red-500 @enderror">{{ old('address', $masterRestaurant->address) }}</textarea>
                            @error('address')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            
                            <div class="mb-4">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('phone_number') border-red-500 @enderror" 
                                    value="{{ old('phone_number', $masterRestaurant->phone_number) }}">
                                @error('phone_number')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="image" class="block text-sm font-medium text-gray-700">Restaurant Image</label>
                                @if ($masterRestaurant->image)
                                    <div class="mt-2 mb-2">
                                        <img src="{{ asset('storage/restaurants/' . $masterRestaurant->image) }}" alt="Restaurant Image" class="w-32 h-32 object-cover rounded-md">
                                        <p class="text-sm text-gray-500 mt-1">Current Image</p>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" 
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-blue-500 hover:file:bg-blue-600 @error('image') border-red-500 @enderror">
                                @error('image')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2">Geolocation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            <div class="mb-4">
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="text" name="latitude" id="latitude" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('latitude') border-red-500 @enderror" 
                                    value="{{ old('latitude', $masterRestaurant->latitude) }}">
                                @error('latitude')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="text" name="longitude" id="longitude" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('longitude') border-red-500 @enderror" 
                                    value="{{ old('longitude', $masterRestaurant->longitude) }}">
                                @error('longitude')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2">Menu Images (Max 7)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            @for ($i = 1; $i <= 7; $i++)
                                <div class="mb-4">
                                    <label for="menu_{{ $i }}" class="block text-sm font-medium text-gray-700">Menu Image {{ $i }}</label>
                                    @php
                                        $menuImageField = "menu_{$i}";
                                    @endphp
                                    @if ($masterRestaurant->$menuImageField)
                                        @if (in_array(pathinfo($masterRestaurant->$menuImageField, PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg', 'gif', 'webp']))
                                            <div class="mt-2 mb-2">
                                                <img src="{{ asset('storage/restaurants/' . $masterRestaurant->$menuImageField) }}" alt="Menu Image {{ $i }}" class="w-32 h-32 object-cover rounded-md">
                                                <p class="text-sm text-gray-500 mt-1">Current Menu Image {{ $i }}</p>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 mt-1">Current Menu Image {{ $i }}: {{ $masterRestaurant->$menuImageField }} (File)</p>
                                        @endif
                                    @endif
                                    <input type="file" name="menu_{{ $i }}" id="menu_{{ $i }}" 
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-blue-500 hover:file:bg-blue-600 @error('menu_' . $i) border-red-500 @enderror">
                                    @error('menu_' . $i)<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            @endfor
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="5" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('description') border-red-500 @enderror">{{ old('description', $masterRestaurant->description) }}</textarea>
                            @error('description')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Update Restaurant
                            </button>
                            <a href="{{ route('master-restaurants.index') }}" class="ml-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>