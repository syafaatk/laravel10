<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Order for Event') }}: {{ $lunchEventUserOrder->lunchEvent->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- Two-column responsive layout: left = order form, right = menu + current order --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- LEFT: Order form --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Order header card --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-medium bg-indigo-50 text-indigo-700">
                                        Your Lunch Order
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ Auth::user()->name }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Event:
                                    <span class="font-medium text-gray-900">
                                        {{ $lunchEventUserOrder->lunchEvent->name }}
                                    </span>
                                    •
                                    <span class="text-gray-500">
                                        {{ $lunchEventUserOrder->lunchEvent->event_date }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Restaurant:
                                    <span class="font-medium text-gray-900">
                                        {{ $lunchEventUserOrder->lunchEvent->restaurant->name ?? '-' }}
                                    </span>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-xs text-gray-500">Current Total</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">
                                    Rp{{ number_format($lunchEventUserOrder->total_price, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id) }}"
                                   class="mt-3 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-800 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M12.293 2.293a1 1 0 011.414 0l4 4A1 1 0 0117 8h-3v7a1 1 0 11-2 0V8H9a1 1 0 01-.707-1.707l4-4z" />
                                        <path d="M3 9a1 1 0 011-1h3v7a1 1 0 001 1h6a1 1 0 001-1V9h3a1 1 0 100-2h-3V4a3 3 0 00-3-3H9a3 3 0 00-3 3v3H3a1 1 0 00-1 1z" />
                                    </svg>
                                    View event details
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Add new menu item --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-md font-semibold text-gray-900">Add New Menu Item</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Isi form secara manual atau klik gambar menu di sebelah kanan untuk melihat daftar menu.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('user-order-details.store', $lunchEventUserOrder->id) }}" id="addItemForm" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- item name --}}
                                <div>
                                    <label for="item_name" class="text-sm font-medium text-gray-700">Menu Item</label>
                                    <input id="item_name" name="item_name" type="text" required
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                           value="{{ old('item_name') }}" placeholder="e.g. Nasi Goreng Hongkong">
                                    <x-input-error :messages="$errors->get('item_name')" class="mt-1" />
                                </div>

                                {{-- type --}}
                                <div>
                                    <label for="type" class="text-sm font-medium text-gray-700">Type</label>
                                    <select id="type" name="type" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="makanan" {{ old('type') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                        <option value="minuman" {{ old('type') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-1" />
                                </div>

                                {{-- quantity --}}
                                <div>
                                    <label for="quantity" class="text-sm font-medium text-gray-700">Quantity</label>
                                    <input id="quantity" name="quantity" type="number" min="1" required
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                           value="{{ old('quantity', 1) }}">
                                    <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
                                </div>

                                {{-- price --}}
                                <div>
                                    <label for="price" class="text-sm font-medium text-gray-700">Price / Item (Rp)</label>
                                    <input id="price" name="price" type="number" step="1" min="0" required
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                           value="{{ old('price') }}">
                                    <x-input-error :messages="$errors->get('price')" class="mt-1" />
                                </div>

                                {{-- notes (ditempat/bungkus) --}}
                                <div>
                                    <label for="notes" class="text-sm font-medium text-gray-700">Order Mode</label>
                                    <select id="notes" name="notes" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="ditempat" {{ old('notes') == 'ditempat' ? 'selected' : '' }}>Makan Ditempat</option>
                                        <option value="bungkus" {{ old('notes') == 'bungkus' ? 'selected' : '' }}>Bungkus</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-dashed border-gray-200">
                                <p class="text-xs text-gray-500">
                                    Tip: gunakan gambar/pdf menu di sebelah kanan untuk melihat detail menu dari restoran.
                                </p>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id) }}"
                                       class="inline-flex items-center px-3 py-2 border border-gray-200 rounded-lg text-xs font-medium bg-gray-50 hover:bg-gray-100 text-gray-700">
                                        Back
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                                        +Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- RIGHT: Menu gallery & current order --}}
                <div class="lg:col-span-3 space-y-6">

                    {{-- Menu gallery --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Restaurant Menu</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Lihat gambar / PDF menu dari restoran. Gunakan pencarian dan filter untuk memudahkan.
                                </p>
                            </div>

                            {{-- Search & filter --}}
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <div class="relative flex-1">
                                    <input id="menuSearch" type="text"
                                           class="w-full rounded-full border-gray-200 shadow-sm py-1.5 pl-8 pr-3 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Search menu asset...">
                                    <span class="absolute inset-y-0 left-2 flex items-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.5 3.5a5 5 0 013.906 8.148l3.673 3.673a.75.75 0 11-1.06 1.06l-3.673-3.672A5 5 0 118.5 3.5zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </div>
                                <select id="filterType"
                                        class="rounded-full border-gray-200 shadow-sm text-xs py-1.5 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="all">All</option>
                                    <option value="image">Image</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                        </div>

                        <div id="menuGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @for ($i = 1; $i <= 7; $i++)
                                @php
                                    $menuImage = 'menu_' . $i;
                                @endphp
                                @if ($lunchEventUserOrder->lunchEvent->restaurant->$menuImage)
                                    @php
                                        $filePath = $lunchEventUserOrder->lunchEvent->restaurant->$menuImage;
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

                        @if (!$lunchEventUserOrder->lunchEvent->restaurant->menu_1 && !$lunchEventUserOrder->lunchEvent->restaurant->menu_2)
                            <p class="text-sm text-gray-500 mt-4">
                                No menu assets uploaded for this restaurant.
                            </p>
                        @endif
                    </div>

                    {{-- Current Order Details --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-md font-semibold text-gray-900">Current Order Details</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Ringkasan item yang sudah kamu tambahkan untuk event ini.
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700">
                                {{ $lunchEventUserOrder->orderDetails->count() }} item(s)
                            </span>
                        </div>

                        @if ($lunchEventUserOrder->orderDetails->count() > 0)
                            <div class="w-full overflow-x-auto rounded-xl border border-gray-100">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-[11px] text-gray-500 uppercase bg-gray-50">
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
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($lunchEventUserOrder->orderDetails as $detail)
                                            <tr class="hover:bg-gray-50/80">
                                                <td class="px-3 py-2">
                                                    <p class="text-sm font-medium text-gray-900">{{ $detail->item_name }}</p>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] capitalize
                                                        {{ $detail->type == 'makanan' ? 'bg-indigo-50 text-indigo-700' : 'bg-sky-50 text-sky-700' }}">
                                                        {{ $detail->type }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 text-center text-sm text-gray-800">
                                                    {{ $detail->quantity }}
                                                </td>
                                                <td class="px-3 py-2 text-right text-sm text-gray-700">
                                                    Rp{{ number_format($detail->price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 py-2 text-right font-medium text-gray-900">
                                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] capitalize bg-gray-100 text-gray-700">
                                                        {{ $detail->notes }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <form action="{{ route('user-order-details.destroy', $detail->id) }}" method="POST"
                                                          onsubmit="return confirm('Delete this item?');" class="inline-flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-50 text-red-600 hover:bg-red-100 text-xs"
                                                                title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M6 2a1 1 0 00-.894.553L4 4H2a1 1 0 000 2v1h16V6a1 1 0 00-1-1h-2l-1.106-1.447A1 1 0 0014 2H6z" />
                                                                <path d="M4 9h12v7a2 2 0 01-2 2H6a2 2 0 01-2-2V9z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">
                                No items added yet. Gunakan form di kiri untuk menambah item.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
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

    {{-- scripts --}}
    <script>
        // quick-fill function (masih bisa dipakai jika nanti ada daftar menu text)
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

        // menu search & filter (client-side simple)
        const menuSearch = document.getElementById('menuSearch');
        const filterType = document.getElementById('filterType');
        const cards = document.querySelectorAll('.menu-card');

        function applyMenuFilter() {
            const searchVal = menuSearch.value.toLowerCase();
            const typeVal = filterType.value;

            cards.forEach(card => {
                const title = (card.dataset.title || '').toLowerCase();
                const type = card.dataset.type || 'other';

                const matchText = title.includes(searchVal);
                const matchType = (typeVal === 'all') || (typeVal === type);

                if (matchText && matchType) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        if (menuSearch && filterType) {
            menuSearch.addEventListener('input', applyMenuFilter);
            filterType.addEventListener('change', applyMenuFilter);
        }

        // focus first error if any
        @if ($errors->any())
            (function () {
                const firstError = document.querySelector('.is-invalid, .border-red-500');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            })();
        @endif
    </script>
</x-app-layout>
