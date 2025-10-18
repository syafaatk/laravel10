<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Restaurant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('master-restaurants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Restaurant Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('phone') border-red-500 @enderror" value="{{ old('phone') }}">
                                @error('phone')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="block text-sm font-medium text-gray-700">Restaurant Image</label>
                                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-blue-500 hover:file:bg-blue-600 @error('image') border-red-500 @enderror">
                                @error('image')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2">Geolocation</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            <div class="mb-4">
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('latitude') border-red-500 @enderror" value="{{ old('latitude') }}">
                                @error('latitude')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('longitude') border-red-500 @enderror" value="{{ old('longitude') }}">
                                @error('longitude')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold mt-6 mb-4 text-gray-800 border-b pb-2">Menu Images (Max 7)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
                            
                            @for ($i = 1; $i <= 7; $i++)
                                <div class="mb-4">
                                    <label for="menu_{{ $i }}" class="block text-sm font-medium text-gray-700">Menu Image {{ $i }}</label>
                                    <input type="file" name="menu_{{ $i }}" id="menu_{{ $i }}" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-blue-500 hover:file:bg-blue-600 @error('menu_' . $i) border-red-500 @enderror">
                                    @error('menu_' . $i)<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            @endfor
                            
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Add Restaurant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>