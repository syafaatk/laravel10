<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Order for Event') }}: {{ $lunchEventUserOrder->lunchEvent->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- Two-column responsive layout: left = order form + details, right = menu --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- LEFT: Order form + current order + sticky summary --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Order header card --}}
                    <div class="bg-white shadow-sm rounded-lg border p-5">
                        <div class="flex items-start justify-between space-x-4">
                            <div>
                                <h3 class="text-lg font-semibold text-indigo-700">
                                    Order for: <span class="text-gray-900">{{ Auth::user()->name }}</span>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Event: <span class="font-medium">{{ $lunchEventUserOrder->lunchEvent->name }}</span>
                                    • <span class="text-gray-500">{{ $lunchEventUserOrder->lunchEvent->event_date }}</span>
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Restaurant: <span class="font-medium">{{ $lunchEventUserOrder->lunchEvent->restaurant->name ?? '-' }}</span>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500">Current Total</p>
                                <p class="text-2xl font-bold text-green-600">Rp{{ number_format($lunchEventUserOrder->total_price, 0, ',', '.') }}</p>
                                <a href="{{ route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id) }}" class="mt-3 inline-block text-xs text-indigo-600 hover:underline">
                                    View event details
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Add new menu item --}}
                    <div class="bg-white shadow-sm rounded-lg border p-5">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Add New Menu Item</h4>

                        <form method="POST" action="{{ route('user-order-details.store', $lunchEventUserOrder->id) }}" id="addItemForm">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- item name --}}
                                <div>
                                    <label for="item_name" class="text-sm font-medium text-gray-700">Menu Item</label>
                                    <input id="item_name" name="item_name" type="text" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('item_name') }}" placeholder="e.g. Nasi Goreng Hongkong">
                                    <x-input-error :messages="$errors->get('item_name')" class="mt-1" />
                                </div>

                                {{-- type --}}
                                <div>
                                    <label for="type" class="text-sm font-medium text-gray-700">Type</label>
                                    <select id="type" name="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="makanan" {{ old('type') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                        <option value="minuman" {{ old('type') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-1" />
                                </div>

                                {{-- quantity --}}
                                <div>
                                    <label for="quantity" class="text-sm font-medium text-gray-700">Quantity</label>
                                    <input id="quantity" name="quantity" type="number" min="1" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('quantity', 1) }}">
                                    <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
                                </div>

                                {{-- price --}}
                                <div>
                                    <label for="price" class="text-sm font-medium text-gray-700">Price / Item (Rp)</label>
                                    <input id="price" name="price" type="number" step="1" min="0" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('price') }}">
                                    <x-input-error :messages="$errors->get('price')" class="mt-1" />
                                </div>

                                {{-- notes (ditempat/bungkus) --}}
                                <div>
                                    <label for="notes" class="text-sm font-medium text-gray-700">Order Mode</label>
                                    <select id="notes" name="notes" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="ditempat" {{ old('notes') == 'ditempat' ? 'selected' : '' }}>Makan Ditempat</option>
                                        <option value="bungkus" {{ old('notes') == 'bungkus' ? 'selected' : '' }}>Bungkus</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-sm text-gray-500">Tip: click an item on the right to quick-fill the form.</div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id) }}" class="inline-flex items-center px-3 py-2 border rounded-md text-sm bg-gray-50 hover:bg-gray-100">
                                        Back
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                                        Add Item
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    
                </div>

                {{-- RIGHT: Menu gallery & quick actions --}}
                <div class="lg:col-span-3">
                    <div class="bg-white shadow-sm rounded-lg border p-5 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-indigo-700">Restaurant Menu</h3>
                                <p class="text-sm text-gray-500">Click an item to quick-fill the add form. Use filters to narrow results.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @for ($i = 1; $i <= 7; $i++)
                                @php
                                    $menuImage = 'menu_' . $i;
                                @endphp
                                @if ($lunchEventUserOrder->lunchEvent->restaurant->$menuImage)
                                    @php
                                        $filePath = $lunchEventUserOrder->lunchEvent->restaurant->$menuImage;
                                        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                        $fileUrl = asset('storage/restaurants/' . $filePath);
                                    @endphp

                                    <div class="border rounded-lg overflow-hidden shadow-sm bg-white">
                                        @if (in_array($fileExtension, ['jpg','jpeg','png','gif']))
                                            <button class="w-full text-left p-3 group" onclick="openImageModal('{{ $fileUrl }}')">
                                                <img src="{{ $fileUrl }}" alt="menu {{ $i }}" class="w-full h-40 object-cover group-hover:opacity-90 transition">
                                                <div class="p-3">
                                                    <p class="text-sm font-medium text-gray-800">Menu asset {{ $i }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">Click to view & quick-add</p>
                                                </div>
                                            </button>
                                        @elseif ($fileExtension == 'pdf')
                                        <div class="p-3">
                                            <div class="h-40 border rounded-md overflow-hidden mb-2 flex items-center justify-center bg-gray-50">
                                                <div class="text-center w-full">
                                                    <div class="text-sm font-medium text-gray-700 mb-2">PDF Menu {{ $i }}</div>
                                                    <button class="inline-flex items-center px-3 py-2 bg-white border rounded-md text-sm hover:bg-gray-100"
                                                        onclick="openPdfModal('{{ $fileUrl }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                                        </svg>
                                                        View PDF (besar)
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Click "View PDF (besar)" to open in large viewer.</p>
                                        </div>
                                        @else
                                            <div class="p-3">
                                                <p class="text-sm text-gray-600">Unsupported asset</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endfor
                        </div>

                        @if (!$lunchEventUserOrder->lunchEvent->restaurant->menu_1 && !$lunchEventUserOrder->lunchEvent->restaurant->menu_2)
                            <p class="text-sm text-gray-500 mt-4">No menu assets uploaded for this restaurant.</p>
                        @endif
                    </div>
                    {{-- Current Order Details --}}
                    <div class="bg-white shadow-sm rounded-lg border p-5">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Current Order Details</h3>

                        @if ($lunchEventUserOrder->orderDetails->count() > 0)
                            <div class="w-full overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2">Menu</th>
                                            <th class="px-3 py-2">Type</th>
                                            <th class="px-3 py-2 text-center">Qty</th>
                                            <th class="px-3 py-2 text-right">Price</th>
                                            <th class="px-3 py-2 text-right">Subtotal</th>
                                            <th class="px-3 py-2 text-center">Notes</th>
                                            <th class="px-3 py-2 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach ($lunchEventUserOrder->orderDetails as $detail)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 py-2">{{ $detail->item_name }}</td>
                                                <td class="px-3 py-2 capitalize">{{ $detail->type }}</td>
                                                <td class="px-3 py-2 text-center">{{ $detail->quantity }}</td>
                                                <td class="px-3 py-2 text-right">Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 text-right font-medium">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                <td class="px-3 py-2 text-center capitalize">{{ $detail->notes }}</td>
                                                <td class="px-3 py-2 text-center">
                                                    <form action="{{ route('user-order-details.destroy', $detail->id) }}" method="POST" onsubmit="return confirm('Delete this item?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a1 1 0 00-.894.553L4 4H2a1 1 0 000 2v10a2 2 0 002 2h12a2 2 0 002-2V6a1 1 0 000-2h-2l-1.106-1.447A1 1 0 0014 2H6z"/></svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No items added yet. Use the form above or pick from the menu on the right.</p>
                        @endif
                    </div>

                    
                </div>
            </div>
        </div>
    </div>

    {{-- Image modal for viewing menu and quick-add --}}
    <div id="menuImageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-60">
        <div class="bg-white rounded-lg max-w-4xl w-full overflow-hidden">
            <div class="flex justify-between items-center p-3 border-b">
                <h4 class="font-semibold">Menu Preview</h4>
                <button onclick="closeImageModal()" class="text-gray-600 hover:text-gray-900">&times;</button>
            </div>
            <div id="menuPreviewContent" class="p-4">
                <img id="menuPreviewImg" src="" alt="menu preview" class="w-full object-contain max-h-[60vh] mx-auto">
                <div class="mt-3 text-sm text-gray-600">Tip: Click any text below to auto-fill the Add Item form.</div>
                <div id="menuQuickAddList" class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    {{-- Optional: server can inject parsed menu items here via JS if available --}}
                </div>
            </div>
        </div>
    </div>

    {{-- PDF modal: large, readable PDF viewer --}}
    <div id="menuPdfModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70">
        <div class="bg-white rounded-lg max-w-8xl w-full h-full overflow-hidden">
            <div class="flex justify-between items-center p-3 border-b">
                <h4 class="font-semibold">PDF Menu Preview</h4>
                <button onclick="closePdfModal()" class="text-gray-600 hover:text-gray-900">&times;</button>
            </div>
            <div class="p-3 h-[calc(100%-56px)]">
                <iframe id="menuPdfFrame" src="" class="w-full h-full border rounded" title="PDF Viewer"></iframe>
            </div>
        </div>
    </div>

    {{-- lightweight scripts: quick-fill + search/filter + modal --}}
    <script>
        // quick-fill function used when clicking a parsed menu item
        function quickFill(itemName, type = 'makanan', price = '') {
            const name = document.getElementById('item_name');
            const typeEl = document.getElementById('type');
            const priceEl = document.getElementById('price');
            const qty = document.getElementById('quantity');
            name.value = itemName;
            typeEl.value = type;
            if (price) priceEl.value = price;
            qty.value = 1;
            name.focus();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function openImageModal(url) {
            document.getElementById('menuPreviewImg').src = url;
            // If you have pre-parsed menu items, populate #menuQuickAddList here.
            document.getElementById('menuImageModal').classList.remove('hidden');
        }
        function closeImageModal() {
            document.getElementById('menuImageModal').classList.add('hidden');
            document.getElementById('menuPreviewImg').src = '';
        }

        // PDF modal controls
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

        // menu search & filter (client-side simple)
        document.getElementById('menuSearch').addEventListener('input', function(e){
            // simple UX improvement placeholder - server-side search recommended for full data
            // here we only show/hide menu cards based on filename text - left as TODO
        });
        document.getElementById('filterType').addEventListener('change', function(e){
            // client-side filter placeholder
        });

        // small helper to preserve UX when form submission has validation errors: focus first error
        @if ($errors->any())
            (function () {
                const firstError = document.querySelector('.is-invalid, .border-red-500');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            })();
        @endif
    </script>
</x-app-layout>