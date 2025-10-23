<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lunch Event Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end mb-6 space-x-3">
                        <!-- buat pesanan -->
                        @if($lunchEvent->status == 'scheduled')
                        <a href="{{ route('lunch-event-user-orders.create', $lunchEvent->id) }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            {{ __('Buat Pesanan') }}
                        </a>
                        @endif
                        <!-- <a href="{{ route('lunch-events.edit', $lunchEvent->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                            {{ __('Edit Event') }}
                        </a> -->
                        <a href="{{ route('lunch-events.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                            {{ __('Back to List') }}
                        </a>
                    </div>

                    <h3 class="text-3xl font-bold text-indigo-700 mb-6 border-b pb-2">{{ $lunchEvent->name }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="md:col-span-2">
                            <dl class="text-gray-900 divide-y divide-gray-200">
                                
                                {{-- Tanggal Event --}}
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-base font-semibold w-full sm:w-1/4">Date:</dt>
                                    <dd class="mt-1 sm:mt-0 sm:w-3/4 text-lg">
                                        {{ $lunchEvent->event_date }}
                                    </dd>
                                </div>

                                {{-- Restoran --}}
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-base font-semibold w-full sm:w-1/4">Restaurant:</dt>
                                    <dd class="mt-1 sm:mt-0 sm:w-3/4 text-lg">
                                        {{-- Asumsi relasi 'restaurant' ada pada model LunchEvent --}}
                                        @if ($lunchEvent->restaurant)
                                            <a href="{{ route('master-restaurants.show', $lunchEvent->restaurant->id) }}" class="text-blue-600 hover:underline font-medium">
                                                {{ $lunchEvent->restaurant->name }}
                                            </a>
                                        @else
                                            <span class="text-red-500">Restaurant Not Found</span>
                                        @endif
                                    </dd>
                                </div>
                                <!-- total biaya -->
                                 
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center">
                                    <dt class="text-base font-semibold w-full sm:w-1/4">Total Cost:</dt>
                                    <dd class="mt-1 sm:mt-0 sm:w-3/4 text-lg">
                                        Rp{{ number_format($totalPrice, 0, ',', '.') }}
                                    </dd>
                                </div>
                                
                                {{-- Deskripsi --}}
                                <div class="py-4">
                                    <dt class="text-base font-semibold mb-2">Description:</dt>
                                    <dd class="text-gray-700 whitespace-pre-wrap">{{ $lunchEvent->description }}</dd>
                                </div>

                                <!-- tampilkan gambar menu -->
                                <div class="py-4">
                                    <dt class="text-base font-semibold mb-2">Menu :</dt>
                                    <dd>
                                        @if ($lunchEvent->restaurant)
                                            <!-- tampilkan menu 1 -7 jika ada-->
                                             
                                            @for ($i = 1; $i <= 7; $i++)
                                                @php
                                                    $menuImage = 'menu_' . $i;
                                                @endphp
                                                @if ($lunchEvent->restaurant->$menuImage)
                                                <!-- jika format gambar tampilkan gambar jika format pdf tampilkan icon pdf-->
                                                 
                                                    @php
                                                        $fileExtension = pathinfo($lunchEvent->restaurant->$menuImage, PATHINFO_EXTENSION);
                                                    @endphp

                                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <a href="{{ asset('storage/restaurants/' . $lunchEvent->restaurant->$menuImage) }}" target="_blank">
                                                            <img src="{{ asset('storage/restaurants/' . $lunchEvent->restaurant->$menuImage) }}" alt="Menu {{ $i }}" class="w-full h-auto object-cover rounded-md mb-2">
                                                        </a>
                                                    @elseif ($fileExtension == 'pdf')
                                                        <a href="{{ asset('storage/restaurants/' . $lunchEvent->restaurant->$menuImage) }}" target="_blank" class="flex items-center text-blue-600 hover:underline mb-2">
                                                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm0 15c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm-1-9h2V7h-2v2z"/>
                                                            </svg>
                                                            View Menu {{ $i }} (PDF)
                                                        </a>
                                                    @else
                                                        <p class="text-gray-500 italic">Unsupported file type for Menu {{ $i }}.</p>
                                                    @endif
                                                @endif
                                            @endfor
                                            
                                        @else
                                            <p class="text-gray-500 italic">No menu image available.</p>
                                        @endif
                                    </dd>
                                </div>
                                <!-- tampilkan user yang memesan -->
                                 
                                <div class="py-4">
                                    <dt class="text-base font-semibold mb-2">Orders :</dt>
                                    <dd>
                                        @if ($lunchEventUserOrders->count() > 0)
                                            <ul class="list-disc pl-5">
                                                @foreach ($lunchEventUserOrders as $order)
                                                    <li class="mb-2">
                                                        <a href="" class="text-blue-600 hover:underline">
                                                            Order by {{ $order->user->name }} - Status: {{ $order->status }} - Total: Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                                        </a>
                                                        <!-- tampilkan list pesanannya -->
                                                        <ul class="list-disc pl-5 text-gray-600">
                                                            <li>Ditempat:</li>
                                                            @forelse ($order->orderDetails ?? [] as $detail)
                                                                @if ($detail->notes === 'ditempat')
                                                                <ul>{{ $detail->quantity }} x {{ $detail->item_name }} (Rp{{ number_format($detail->price, 0, ',', '.') }}) - {{$detail->notes}}</ul>
                                                                @endif
                                                            @empty
                                                                {{-- Display a friendly message if the collection is null or empty --}}
                                                                <li class="italic text-gray-500">No specific order items were found for this event.</li>
                                                            @endforelse
                                                            <li>Bungkus:</li>
                                                            @forelse ($order->orderDetails ?? [] as $detail)
                                                                @if ($detail->notes === 'bungkus')
                                                                <ul>{{ $detail->quantity }} x {{ $detail->item_name }} (Rp{{ number_format($detail->price, 0, ',', '.') }}) - {{$detail->notes}}</ul>
                                                                @endif
                                                            @empty
                                                                {{-- Display a friendly message if the collection is null or empty --}}
                                                                <li class="italic text-gray-500">No specific order items were found for this event.</li>
                                                            @endforelse
                                                        </ul>
                                                    </li>
                                                    
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-500 italic">No orders yet for this event.</p>
                                        @endif
                                    </dd>
                                </div>
                                
                            </dl>
                        </div>

                        {{-- Card Restoran Terkait --}}
                        <div class="md:col-span-1 border rounded-lg shadow-lg p-4 bg-gray-50">
                            <h4 class="text-xl font-bold mb-3 text-indigo-600">Restaurant Info</h4>
                            @if ($lunchEvent->restaurant)
                                <p class="text-sm text-gray-700 mb-2">Address: {{ $lunchEvent->restaurant->address ?? '-' }}</p>
                                <p class="text-sm text-gray-700 mb-2">Phone: {{ $lunchEvent->restaurant->phone ?? '-' }}</p>
                                @if ($lunchEvent->restaurant->image)
                                    <img src="{{ asset('storage/restaurants/' . $lunchEvent->restaurant->image) }}" alt="Restaurant Image" class="w-full h-70 object-cover rounded-md mt-3">
                                @endif
                                @if ($lunchEvent->image)
                                    <img src="{{ asset('storage/' . $lunchEvent->image) }}" alt="Eviden Image" class="w-full h-70 object-cover rounded-md mt-3">
                                @endif 
                                <!-- foto nota -->
                                @if ($lunchEvent->nota)
                                    <img src="{{ asset('storage/' . $lunchEvent->nota) }}" alt="Nota Image" class="w-full h-70 object-cover rounded-md mt-3">
                                @endif 
                            @else
                                <p class="text-gray-500 italic">No associated restaurant details to display.</p>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>