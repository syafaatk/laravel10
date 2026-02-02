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
                     
                    <h3 class="mt-5 text-xl font-semibold mb-6 border-b pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Lunch Events History & Evidence
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if(isset($masterRestaurant->restaurantEvent) && $masterRestaurant->restaurantEvent && count($masterRestaurant->restaurantEvent))
                            @foreach ($masterRestaurant->restaurantEvent as $event)
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                                    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-gray-900 leading-tight">{{ $event->name }}</h4>
                                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                                </p>
                                            </div>
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $event->status == 'done' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $event->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-4 flex-grow">
                                        @php 
                                        $attachments = collect();
                                        if($attachments = $event->reimbursements->where('status', 'approved')) {
                                            $attachments = $event->reimbursements->where('status', 'approved')->filter(fn($r) => $r->attachment || $r->attachment_note);
                                        } elseif ($attachments = $event->reimbursements->where('status', 'done')) {
                                            $attachments = $event->reimbursements->where('status', 'done')->filter(fn($r) => $r->attachment || $r->attachment_note);
                                        }
                                        @endphp

                                        @if($attachments->count() > 0)
                                            <div class="space-y-3">
                                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Evidence Gallery</p>
                                                <div class="grid grid-cols-4 gap-2">
                                                    @foreach($attachments as $reimbursement)
                                                        {{-- Handle Receipt Image --}}
                                                        @if($reimbursement->attachment && in_array(pathinfo($reimbursement->attachment, PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg', 'gif', 'webp']))
                                                            <div class="relative aspect-square group cursor-pointer overflow-hidden rounded-lg border border-gray-200" 
                                                                onclick="openModal('{{ asset('storage/' . $reimbursement->attachment) }}', 'Receipt - {{ $event->name }}')">
                                                                <img src="{{ asset('storage/' . $reimbursement->attachment) }}" 
                                                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-110" alt="Receipt">
                                                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                                                                <div class="absolute top-0 right-0 p-0.5 bg-indigo-600 rounded-bl-lg">
                                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        {{-- Handle Event Photo Image --}}
                                                        @if($reimbursement->attachment_note && in_array(pathinfo($reimbursement->attachment_note, PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg', 'gif', 'webp']))
                                                            <div class="relative aspect-square group cursor-pointer overflow-hidden rounded-lg border border-gray-200" 
                                                                onclick="openModal('{{ asset('storage/' . $reimbursement->attachment_note) }}', 'Event Photo - {{ $event->name }}')">
                                                                <img src="{{ asset('storage/' . $reimbursement->attachment_note) }}" 
                                                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-110" alt="Event Photo">
                                                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                                                                <div class="absolute top-0 right-0 p-0.5 bg-amber-500 rounded-bl-lg">
                                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812-1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-24 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-xl">
                                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="text-[10px] text-gray-400 mt-2">No evidence uploaded</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4 pt-0">
                                        <a href="{{ route('lunch-events.show', $event->id) }}" 
                                        class="flex items-center justify-center w-full py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg hover:bg-indigo-100 transition-colors group">
                                            View Full Details
                                            <svg class="w-3 h-3 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full py-12 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                <p class="text-gray-500">No lunch events recorded for this restaurant.</p>
                            </div>
                        @endif
                    </div>

                    <div id="imageModal" class="fixed inset-0 z-[100] hidden bg-black/90 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeModal()">
                        <div class="relative max-w-5xl w-full flex flex-col items-center" onclick="event.stopPropagation()">
                            <button class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors" onclick="closeModal()">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <img id="modalImage" src="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
                            <div class="mt-4 bg-white/10 px-4 py-2 rounded-full backdrop-blur-md">
                                <p id="modalCaption" class="text-white font-medium text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openModal(imageSrc, caption) {
                            document.getElementById('modalImage').src = imageSrc;
                            document.getElementById('modalCaption').innerText = caption;
                            const modal = document.getElementById('imageModal');
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden'; // prevent scroll
                        }

                        function closeModal() {
                            const modal = document.getElementById('imageModal');
                            modal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }

                        // Close on ESC key
                        document.addEventListener('keydown', function(event) {
                            if (event.key === "Escape") closeModal();
                        });
                    </script>
                    
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>