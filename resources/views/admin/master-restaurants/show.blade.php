<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Restaurant Details') }}: {{ $masterRestaurant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end mb-6 space-x-2">
                        <a href="{{ route('master-restaurants.edit', $masterRestaurant->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                            Edit Restaurant
                        </a>
                        <a href="{{ route('master-restaurants.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                            Back to List
                        </a>
                    </div>
                    
                    <h3 class="text-2xl font-bold mb-4 border-b pb-2">{{ $masterRestaurant->name }}</h3>

                    {{-- Detail Umum dan Gambar Utama --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <div class="md:col-span-2">
                            <dl class="divide-y divide-gray-100">
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Address</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $masterRestaurant->address ?? '-' }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Phone</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $masterRestaurant->phone_number ?? '-' }}</dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Geolocation</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        Latitude: **{{ $masterRestaurant->latitude ?? '-' }}**, Longitude: **{{ $masterRestaurant->longitude ?? '-' }}**
                                        <!-- view google map -->
                                        @if ($masterRestaurant->latitude && $masterRestaurant->longitude)
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $masterRestaurant->latitude }},{{ $masterRestaurant->longitude }}" target="_blank" class="text-blue-600 hover:underline ml-2">View on Map</a>
                                        @endif
                                    </dd>
                                </div>
                                <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Description</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 whitespace-pre-wrap">{{ $masterRestaurant->description ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Gambar Utama --}}
                        <div class="md:col-span-1">
                            <h4 class="text-lg font-semibold mb-2">Restaurant Image</h4>
                            @if ($masterRestaurant->image)
                                <img src="{{ asset('storage/restaurants/' . $masterRestaurant->image) }}" alt="{{ $masterRestaurant->name }}" class="w-full h-auto object-cover rounded-lg shadow-lg">
                            @else
                                <p class="text-sm text-gray-500">No main image available.</p>
                            @endif
                        </div>
                    </div>

                    <hr class="my-8">

                    {{-- Galeri Gambar Menu --}}
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Menu Gallery</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @php
                            $hasMenuImage = false;
                        @endphp

                        @for ($i = 1; $i <= 7; $i++)
                            @php
                                $menuImageField = "menu_{$i}";
                            @endphp

                            @if ($masterRestaurant->$menuImageField)
                                @php $hasMenuImage = true; @endphp
                                    @if (in_array(pathinfo($masterRestaurant->$menuImageField, PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg', 'gif', 'webp']))
                                        <div class="shadow-md rounded-lg overflow-hidden border border-gray-200">
                                            <a href="{{ asset('storage/restaurants/' . $masterRestaurant->$menuImageField) }}" target="_blank">
                                                <img src="{{ asset('storage/restaurants/' . $masterRestaurant->$menuImageField) }}" alt="Menu {{ $i }}" class="w-full h-32 object-cover transition duration-300 ease-in-out hover:scale-105">
                                            </a>
                                            <p class="text-xs text-center p-1 text-gray-600">Menu {{ $i }}</p>
                                        </div>
                                    @else 
                                        <!-- pdf -->
                                        <div class="shadow-md rounded-lg overflow-hidden border border-gray-200 flex flex-col items-center justify-center p-2">
                                            <a href="{{ asset('storage/restaurants/' . $masterRestaurant->$menuImageField) }}" target="_blank" class="text-blue-500 hover:underline text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mb-2 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25m-4.5 3v7.5m-2.25-3h4.5" />
                                                </svg>
                                                View PDF
                                            </a>
                                            <p class="text-xs text-center p-1 text-gray-600">Menu {{ $i }} (PDF)</p>
                                        </div>
                                    @endif
                                    
                            @endif
                        @endfor

                        @if (!$hasMenuImage)
                            <div class="col-span-full">
                                <p class="text-gray-500 italic">No menu images uploaded.</p>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>