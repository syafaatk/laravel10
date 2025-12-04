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
                                    <div id="menuGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @for ($i = 1; $i <= 7; $i++)
                                            @php
                                                $menuImage = 'menu_' . $i;
                                            @endphp
                                            @if ($lunchEvent->restaurant->$menuImage)
                                                @php
                                                    $filePath = $lunchEvent->restaurant->$menuImage;
                                                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                                    $fileUrl = asset('storage/restaurants/' . $filePath);
                                                    $isImage = in_array($fileExtension, ['jpg','jpeg','png','gif','webp']);
                                                    $assetType = $isImage ? 'image' : ($fileExtension == 'pdf' ? 'pdf' : 'other');
                                                @endphp

                                                <div class="menu-card group border border-gray-100 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer"
                                                    data-title="Menu asset {{ $i }}"
                                                    data-type="{{ $assetType }}">
                                                    @if ($isImage)
                                                        <div class="relative">
                                                            <img src="{{ $fileUrl }}" alt="menu {{ $i }}"
                                                                class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                                                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                                            <div class="absolute top-2 left-2 inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium bg-black/60 text-white">
                                                                Image Menu {{ $i }}
                                                            </div>
                                                            <div class="absolute bottom-2 right-2">
                                                                <button type="button"
                                                                        onclick="openImageModal('{{ $fileUrl }}')"
                                                                        class="inline-flex items-center px-2.5 py-1.5 rounded-full text-[11px] font-medium bg-white/90 text-gray-800 shadow-sm hover:bg-white">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path d="M4 3a2 2 0 00-2 2v10.5A1.5 1.5 0 003.5 17H14a2 2 0 002-2V5a2 2 0 00-2-2H4z" />
                                                                        <path d="M6 11l1.5-2 1.75 2.333L11 9l3 4H6z" />
                                                                    </svg>
                                                                    View Image
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="p-3">
                                                            <p class="text-sm font-medium text-gray-900">Menu asset {{ $i }}</p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Klik tombol <span class="font-semibold">View Image</span> untuk melihat detail.
                                                            </p>
                                                        </div>
                                                    @elseif ($fileExtension == 'pdf')
                                                        <div class="p-4 h-40 flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white shadow-sm mb-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M6 2a2 2 0 00-2 2v16c0 1.1.9 2 2 2h12a2 2 0 002-2V8.828A2 2 0 0019.414 7L15 2.586A2 2 0 0013.586 2H6z" />
                                                                    <path d="M9 15h1v-1H9v1zm0-2h1v-2H9v2zm2 2h1v-3h-1v3zm2 0h2v-1h-1v-.5h1v-1h-1V13h1v-1h-2v3zm-5 2h8v1H8v-1z" class="text-white" />
                                                                </svg>
                                                            </div>
                                                            <p class="text-sm font-semibold text-gray-800">PDF Menu {{ $i }}</p>
                                                            <p class="text-[11px] text-gray-500 text-center mt-1">
                                                                Klik untuk membuka PDF dalam tampilan besar.
                                                            </p>
                                                            <button type="button"
                                                                    onclick="openPdfModal('{{ $fileUrl }}')"
                                                                    class="mt-3 inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-medium bg-white text-gray-800 border border-gray-200 shadow-sm hover:bg-gray-50">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M4 4h12v2H4V4zm0 4h12v2H4V8zm0 4h8v2H4v-2z" />
                                                                </svg>
                                                                View PDF
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="p-3 h-40 flex items-center justify-center bg-gray-50">
                                                            <p class="text-sm text-gray-500">Unsupported asset</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <!-- tampilkan user yang memesan -->
                                 
                                <div class="py-4">
                                    <dt class="text-base font-semibold mb-4">Orders:</dt>
                                    <dd>
                                        @if ($lunchEventUserOrders->count() > 0)
                                            {{-- Summary Statistics --}}
                                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                                <h5 class="font-semibold text-blue-900 mb-2">Order Summary</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <p class="text-gray-600">Total Orders</p>
                                                        <p class="text-xl font-bold text-blue-600">{{ $lunchEventUserOrders->count() }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600">Total Items</p>
                                                        <p class="text-xl font-bold text-blue-600">
                                                            {{ $lunchEventUserOrders->sum(fn($o) => $o->orderDetails->sum('quantity')) }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600">Total Revenue</p>
                                                        <p class="text-xl font-bold text-blue-600">
                                                            Rp{{ number_format($lunchEventUserOrders->sum('total_price'), 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-600">Avg Order Value</p>
                                                        <p class="text-xl font-bold text-blue-600">
                                                            Rp{{ number_format($lunchEventUserOrders->avg('total_price'), 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Build aggregated data: separate by order type and item type --}}
                                            @php
                                                $groups = [
                                                    'ditempat' => ['makanan' => [], 'minuman' => []],
                                                    'bungkus'  => ['makanan' => [], 'minuman' => []],
                                                ];

                                                // helper fn to guess item type if not present
                                                $guessType = function($name) {
                                                    return preg_match('/\b(air|es|teh|jus|juice|jeruk|kopi|capuchino|cappu|capuccino|kopi|latte|capuchino|es|mango)\b/i', $name)
                                                        ? 'minuman' : 'makanan';
                                                };

                                                foreach ($lunchEventUserOrders as $order) {
                                                    foreach ($order->orderDetails as $detail) {
                                                        $orderNote = strtolower($detail->notes ?? '');
                                                        $orderType = $orderNote === 'bungkus' ? 'bungkus' : 'ditempat';
                                                        $itemType = $detail->type ?? $detail->type ?? $guessType($detail->item_name);

                                                        $key = $detail->item_name . '|' . $detail->price . '|' . $itemType;

                                                        if (!isset($groups[$orderType][$itemType][$key])) {
                                                            $groups[$orderType][$itemType][$key] = [
                                                                'item_name' => $detail->item_name,
                                                                'price' => $detail->price,
                                                                'quantity' => 0,
                                                                'users' => [],
                                                            ];
                                                        }

                                                        $groups[$orderType][$itemType][$key]['quantity'] += $detail->quantity;
                                                        $groups[$orderType][$itemType][$key]['users'][] = $order->user->short_name;
                                                    }
                                                }

                                                // Build formatted copy text per orderType
                                                $copyTexts = [];
                                                foreach (['ditempat','bungkus'] as $ot) {
                                                    $lines = [];
                                                    foreach (['makanan','minuman'] as $it) {
                                                        if (!empty($groups[$ot][$it])) {
                                                            foreach ($groups[$ot][$it] as $g) {
                                                                $users = implode(', ', array_unique($g['users']));
                                                                $price = number_format($g['price'], 0, ',', '.');
                                                                $lines[] = "{$g['quantity']} x {$g['item_name']} (Rp{$price}) – {$users}";
                                                            }
                                                        }
                                                    }
                                                    $copyTexts[$ot] = implode("\n", $lines);
                                                }
                                            @endphp

                                            {{-- Render Ditempat --}}
                                            <div class="mb-6 p-2 bg-green-50 rounded-lg border border-green-300">
                                                <div class="flex items-center justify-between">
                                                    <h5 class="font-bold text-green-900 mb-3 text-lg">Makan Ditempat</h5>
                                                    <div class="flex items-center space-x-2">
                                                        <button class="px-3 py-1 text-sm bg-white border rounded hover:bg-gray-100" onclick="copySection('ditempat')">
                                                            Copy Pesanan
                                                        </button>
                                                        @if(Auth::user()->hasRole('admin'))
                                                            <span class="text-xs text-gray-500">Admin: dapat edit</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if(!empty($groups['ditempat']['makanan']))
                                                    <div class="mb-3">
                                                        <p class="font-semibold text-gray-800">Makanan</p>
                                                        <ul class="mt-2 space-y-2">
                                                            @foreach ($groups['ditempat']['makanan'] as $itemKey => $item)
                                                                <li class="flex items-start justify-between px-3 py-2 bg-white rounded border border-green-100">
                                                                    <div class="flex-1">
                                                                        <div class="text-gray-900 font-semibold">{{ $item['quantity'] }} x {{ $item['item_name'] }} 
                                                                          :  Rp{{ number_format($item['price'], 0, ',', '.') }} • <span class="text-sm text-gray-600">  {{ implode(', ', array_unique($item['users'])) }}</div>
                                                                    </div>
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <button type="button" class="ml-4 px-3 py-1 bg-blue-500 text-white rounded text-sm" onclick="openEditModal('{{ $itemKey }}','{{ $item['item_name'] }}',{{ $item['quantity'] }},{{ $item['price'] }},'makanan','ditempat')">Edit</button>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if(!empty($groups['ditempat']['minuman']))
                                                    <div>
                                                        <p class="font-semibold text-gray-800">Minuman</p>
                                                        <ul class="mt-2 space-y-2">
                                                            @foreach ($groups['ditempat']['minuman'] as $itemKey => $item)
                                                                <li class="flex items-start justify-between px-3 py-2 bg-white rounded border border-green-100">
                                                                    <div class="flex-1">
                                                                        <div class="text-gray-900 font-semibold">{{ $item['quantity'] }} x {{ $item['item_name'] }}
                                                                        : Rp{{ number_format($item['price'], 0, ',', '.') }} • <span class="text-sm text-gray-600"> {{ implode(', ', array_unique($item['users'])) }}</span></div>
                                                                    </div>
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <button type="button" class="ml-4 px-3 py-1 bg-blue-500 text-white rounded text-sm" onclick="openEditModal('{{ $itemKey }}','{{ $item['item_name'] }}',{{ $item['quantity'] }},{{ $item['price'] }},'minuman','ditempat')">Edit</button>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Render Bungkus --}}
                                            <div class="mb-6 p-4 bg-orange-50 rounded-lg border border-orange-300">
                                                <div class="flex items-center justify-between">
                                                    <h5 class="font-bold text-orange-900 mb-3 text-lg">Bungkus</h5>
                                                    <div class="flex items-center space-x-2">
                                                        <button class="px-3 py-1 text-sm bg-white border rounded hover:bg-gray-100" onclick="copySection('bungkus')">
                                                            Copy Pesanan
                                                        </button>
                                                    </div>
                                                </div>

                                                @if(!empty($groups['bungkus']['makanan']))
                                                    <div class="mb-3">
                                                        <p class="font-semibold text-gray-800">Makanan</p>
                                                        <ul class="mt-2 space-y-2">
                                                            @foreach ($groups['bungkus']['makanan'] as $itemKey => $item)
                                                                <li class="flex items-start justify-between px-3 py-2 bg-white rounded border border-orange-100">
                                                                    <div class="flex-1">
                                                                        <div class="text-gray-900 font-semibold">{{ $item['quantity'] }} x {{ $item['item_name'] }} 
                                                                            : Rp{{ number_format($item['price'], 0, ',', '.') }} • <span class="text-sm text-gray-600"> {{ implode(', ', array_unique($item['users'])) }}</span></div>
                                                                    </div>
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <button type="button" class="ml-4 px-3 py-1 bg-blue-500 text-white rounded text-sm" onclick="openEditModal('{{ $itemKey }}','{{ $item['item_name'] }}',{{ $item['quantity'] }},{{ $item['price'] }},'makanan','bungkus')">Edit</button>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if(!empty($groups['bungkus']['minuman']))
                                                    <div>
                                                        <p class="font-semibold text-gray-800">Minuman</p>
                                                        <ul class="mt-2 space-y-2">
                                                            @foreach ($groups['bungkus']['minuman'] as $itemKey => $item)
                                                                <li class="flex items-start justify-between px-3 py-2 bg-white rounded border border-orange-100">
                                                                    <div class="flex-1">
                                                                        <div class="text-gray-900 font-semibold">{{ $item['quantity'] }} x {{ $item['item_name'] }} 
                                                                            : Rp{{ number_format($item['price'], 0, ',', '.') }} • <span class="text-sm text-gray-600"> {{ implode(', ', array_unique($item['users'])) }}</span></div>
                                                                    </div>
                                                                    @if(Auth::user()->hasRole('admin'))
                                                                        <button type="button" class="ml-4 px-3 py-1 bg-blue-500 text-white rounded text-sm" onclick="openEditModal('{{ $itemKey }}','{{ $item['item_name'] }}',{{ $item['quantity'] }},{{ $item['price'] }},'minuman','bungkus')">Edit</button>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Hidden copy texts used by JS --}}
                                            <textarea id="copy-ditempat" class="hidden">{{ $copyTexts['ditempat'] }}</textarea>
                                            <textarea id="copy-bungkus" class="hidden">{{ $copyTexts['bungkus'] }}</textarea>

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
    {{-- Modal Edit Item --}}
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Order Item</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" action="{{ route('lunch-event-user-orders.update-item', $lunchEvent->id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" id="itemKey" name="item_key">
                <!-- <input type="hidden" id="itemType" name="type">
                <input type="hidden" id="orderType" name="order_type"> -->

                {{-- Nama Makanan/Minuman --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Makanan/Minuman</label>
                    <input type="text" id="itemName" name="item_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Total Pesanan --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Pesanan (Qty)</label>
                    <input type="number" id="itemQuantity" name="quantity" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Harga --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" id="itemPrice" name="price" min="0" step="100" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Tipe Item (Makanan/Minuman) --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Item</label>
                    <select id="itemType" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>

                {{-- Tipe Pemesanan (Ditempat/Bungkus) --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pemesanan</label>
                    <select id="orderType" name="order_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <!-- selected  -->
                        <option value="ditempat">Makan Ditempat</option>
                        <option value="bungkus">Bungkus</option>
                    </select>
                </div>

                {{-- Tombol Action --}}
                <div class="flex justify-between">
                    <button type="button" onclick="closeEditModal()" class="px-2 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <div class="space-x-2">
                        <button type="submit" class="px-2 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Simpan
                        </button>
                        <button type="button" id="deleteBtn" class="px-2 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="deleteItem()">
                            Hapus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Image modal for viewing menu --}}
    <div id="menuImageModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="relative bg-white rounded-2xl max-w-4xl w-full overflow-hidden shadow-xl">
            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3a2 2 0 00-2 2v10.5A1.5 1.5 0 003.5 17H14a2 2 0 002-2V5a2 2 0 00-2-2H4z" />
                            <path d="M6 11l1.5-2 1.75 2.333L11 9l3 4H6z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Menu Preview</h4>
                        <p class="text-[11px] text-gray-500">Scroll bila gambar memanjang ke bawah.</p>
                    </div>
                </div>
                <button onclick="closeImageModal()"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                    <span class="sr-only">Close</span>
                    &times;
                </button>
            </div>
            <div id="menuPreviewContent" class="p-4 max-h-[80vh] overflow-auto bg-gray-50">
                <img id="menuPreviewImg" src="" alt="Menu Preview"
                     class="w-full object-contain rounded-lg shadow-sm">
            </div>
        </div>
    </div>

    {{-- PDF modal: large, readable PDF viewer --}}
    <div id="menuPdfModal"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="relative bg-white rounded-2xl max-w-6xl w-full h-[90vh] overflow-hidden shadow-2xl">
            <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 bg-gray-50/90">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-rose-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-rose-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 2a2 2 0 00-2 2v16c0 1.1.9 2 2 2h12a2 2 0 002-2V8.828A2 2 0 0019.414 7L15 2.586A2 2 0 0013.586 2H6z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">PDF Menu Preview</h4>
                        <p class="text-[11px] text-gray-500">
                            Kamu dapat zoom dari browser (Ctrl + Scroll / pinch).
                        </p>
                    </div>
                </div>
                <button onclick="closePdfModal()"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                    <span class="sr-only">Close</span>
                    &times;
                </button>
            </div>
            <div class="p-3 h-[calc(100%-48px)] bg-slate-50">
                <iframe id="menuPdfFrame" src="" class="w-full h-full border border-slate-200 rounded-xl bg-white"
                        title="PDF Viewer"></iframe>
            </div>
        </div>
    </div>
    {{-- JavaScript untuk Modal & Copy --}}
    <script>
        function openImageModal(url) {
            document.getElementById('menuPreviewImg').src = url;
            document.getElementById('menuImageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('menuImageModal').classList.add('hidden');
            document.getElementById('menuPreviewImg').src = '';
        }

        function openPdfModal(url) {
            const frame = document.getElementById('menuPdfFrame');
            frame.src = url;
            document.getElementById('menuPdfModal').classList.remove('hidden');
        }

        function closePdfModal() {
            const frame = document.getElementById('menuPdfFrame');
            frame.src = '';
            document.getElementById('menuPdfModal').classList.add('hidden');
        }

        // ESC untuk menutup modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closePdfModal();
            }
        });
        
        
        
        function openEditModal(itemKey, itemName, quantity, price, itemType, orderType) {
            document.getElementById('itemKey').value = itemKey;
            document.getElementById('itemName').value = itemName;
            document.getElementById('itemQuantity').value = quantity;
            document.getElementById('itemPrice').value = price;
            document.getElementById('itemType').value = itemType;
            document.getElementById('orderType').value = orderType;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function deleteItem() {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                const form = document.getElementById('editForm');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.value = 'DELETE';
                form.appendChild(input);
                form.submit();
            }
        }

        // Copy formatted section text to clipboard
        function copySection(section) {
            const id = section === 'ditempat' ? 'copy-ditempat' : 'copy-bungkus';
            const ta = document.getElementById(id);
            if (!ta) return;
            const text = ta.value.trim();
            if (!text) {
                alert('Tidak ada item untuk disalin.');
                return;
            }
            navigator.clipboard.writeText((section === 'ditempat' ? 'Makan Ditempat:\n' : 'Bungkus:\n') + text)
                .then(() => {
                    alert('Pesanan disalin ke clipboard.');
                })
                .catch(() => {
                    // fallback
                    ta.style.display = 'block';
                    ta.select();
                    document.execCommand('copy');
                    ta.style.display = 'none';
                    alert('Pesanan disalin ke clipboard.');
                });
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>