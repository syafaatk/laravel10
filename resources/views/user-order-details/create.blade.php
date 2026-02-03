<x-app-layout>
    <style>
        .select2-container {
            z-index: 10001 !important;
        }

        .select2-dropdown {
            z-index: 10002 !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 0.5rem;
        }

        .select2-container--default .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px !important;
            color: #374151 !important;
        }

        iframe {
            pointer-events: auto;
        }

    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Order for Event') }}: {{ $lunchEventUserOrder->lunchEvent->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- Two-column responsive layout: left = order form, right = menu + current order --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT: Order List & Summary --}}
                <div class="lg:col-span-4 space-y-6 order-2 lg:order-1">

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
                                <p class="text-sm text-gray-500 mt-1">
                                    Deskripsi:<hr>
                                    <span class="font-medium text-gray-900 text-xl">
                                        {{ $lunchEventUserOrder->lunchEvent->description ?? '-' }}
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

                    {{-- Button to trigger Modal --}}
                    <button onclick="openOrderModal()" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Pesanan
                    </button>

                    {{-- Current Order Details --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-md font-semibold text-gray-900">Current Order Details</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Ringkasan item yang sudah kamu tambahkan.
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
                                            <th class="px-3 py-2 text-right">Price</th>
                                            <th class="px-3 py-2 text-center">Act</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($lunchEventUserOrder->orderDetails as $detail)
                                            <tr class="hover:bg-gray-50/80">
                                                <td class="px-3 py-2">
                                                    <p class="text-sm font-medium text-gray-900">{{ $detail->item_name }}</p>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $detail->quantity }}x @ {{ number_format($detail->price, 0, ',', '.') }}
                                                        <!-- badge ditempat dan bungkus -->                                                    
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $detail->notes == 'bungkus' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                                            {{ ucfirst($detail->notes) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2 text-right font-medium text-gray-900">
                                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <form action="{{ route('user-order-details.destroy', $detail->id) }}" method="POST"
                                                          onsubmit="return confirm('Delete this item?');" class="inline-flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-800"
                                                                title="Delete">
                                                            <!-- icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                                No items added yet.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- RIGHT: Menu gallery (Full) --}}
                <div class="lg:col-span-8 space-y-6 order-1 lg:order-2">

                    {{-- Menu gallery --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Restaurant Menu</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Daftar menu dari restoran.
                                </p>
                            </div>
                        </div>

                        <div id="menuGrid" class="space-y-8">
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

                                    <div class="menu-card" data-title="Menu asset {{ $i }}" data-type="{{ $assetType }}">
                                        <h4 class="text-sm font-bold text-gray-700 mb-2">Menu Asset {{ $i }}</h4>
                                        @if ($isImage)
                                            <img src="{{ $fileUrl }}" alt="menu {{ $i }}" class="w-full h-auto rounded-lg shadow-md">
                                        @elseif ($fileExtension == 'pdf')
                                            <iframe src="{{ $fileUrl }}" class="w-full h-[800px] rounded-lg shadow-md border border-gray-200"></iframe>
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
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL for Form --}}
    <!-- pindahkan ke sebelah kanan -->

    <!-- SIDE PANEL MODAL -->
    <div id="orderModal"
        class="fixed top-0 right-0 h-screen w-[420px] bg-white shadow-2xl z-50 flex flex-col"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                Tambah Menu
            </h3>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- BODY (SCROLLABLE) -->
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <form method="POST" action="{{ route('user-order-details.store', $lunchEventUserOrder->id) }}" id="addItemForm" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-3">
                    {{-- item name --}}
                    
                    <div>
                        <label for="item_name_select" class="text-sm font-medium text-gray-700">Menu Item</label>
                        <!-- Hidden input to store the actual item name for submission -->
                        <input type="hidden" name="item_name" id="hidden_item_name" value="{{ old('item_name') }}">
                        <!-- Select2 for user interaction -->
                        <select id="item_name_select" name="item_name_select" required
                                class="select2 mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option></option> {{-- Empty option for placeholder --}}
                            @foreach ($existingMenuItems as $menuItem)
                                @php $value = $menuItem['item_name'] . '|' . $menuItem['price'] . '|' . $menuItem['type']; @endphp
                                <option value="{{ $value }}"
                                        data-price="{{ $menuItem['price'] }}" 
                                        data-type="{{ $menuItem['type'] }}"
                                        >
                                    {{ $menuItem['item_name'] }} ({{ ucfirst($menuItem['type']) }}) - Rp{{ number_format($menuItem['price'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('item_name')" class="mt-1" />
                    </div>

                    {{-- type --}}
                    <div>
                        <label for="type" class="text-sm font-medium text-gray-700">Type</label>
                        <select id="type" name="type" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="makanan" {{ old('type') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="minuman" {{ old('type') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1" />
                    </div>

                    {{-- quantity --}}
                    <div>
                        <label for="quantity" class="text-sm font-medium text-gray-700">Quantity</label>
                        <input id="quantity" name="quantity" type="number" min="1" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            value="{{ old('quantity', 1) }}">
                        <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
                    </div>

                    {{-- price --}}
                    <div>
                        <label for="price" class="text-sm font-medium text-gray-700">Price / Item (Rp)</label>
                        <input id="price" name="price" type="number" step="1" min="0" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            value="{{ old('price') }}">
                        <x-input-error :messages="$errors->get('price')" class="mt-1" />
                    </div>

                    {{-- notes (ditempat/bungkus) --}}
                    <div>
                        <label for="notes" class="text-sm font-medium text-gray-700">Order Mode</label>
                        <select id="notes" name="notes" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="ditempat" {{ old('notes') == 'ditempat' ? 'selected' : '' }}>Makan Ditempat</option>
                            <option value="bungkus" {{ old('notes') == 'bungkus' ? 'selected' : '' }}>Bungkus</option>
                        </select>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>

                <div class="flex items-center justify-end pt-3 border-t border-dashed border-gray-200">
                    <button type="button" onclick="closeOrderModal()" class="mr-3 inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-xs font-medium bg-white hover:bg-gray-50 text-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                        + Add Item
                    </button>
                </div>
            </form>
            <!-- list pesanan yang sudah ada-->
            <div class="mt-4">
                @foreach ($lunchEventUserOrder->orderDetails as $orderDetail)
                    <div class="flex justify-between items-center p-2 border-b text-sm">
                        <div>
                            <span class="font-medium">{{ $orderDetail->item_name }}</span>
                            <span class="text-sm text-gray-500 ml-2">({{ ucfirst($orderDetail->type) }})</span>
                        </div>
                        <div class="text-right">
                            <span class="font-medium">Rp{{ number_format($orderDetail->price, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500 ml-2">x{{ $orderDetail->quantity }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- FOOTER (STICKY) -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Total Saat Ini</span>
                <span class="text-xl font-bold text-emerald-600">
                    Rp{{ number_format($lunchEventUserOrder->total_price, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    
    <script>
        // 1. Fungsi Modal (Diletakkan di luar agar global)
        function openOrderModal() {
            const modal = document.getElementById('orderModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeOrderModal() {
            const modal = document.getElementById('orderModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        $(document).ready(function() {
            const itemNameSelect = $('#item_name_select');
            const hiddenItemName = $('#hidden_item_name');
            const typeSelect = $('#type');
            const priceInput = $('#price');

            // 2. Inisialisasi Select2
            itemNameSelect.select2({
                tags: true,
                placeholder: "Pilih atau ketik menu baru...",
                allowClear: true,
                width: '100%',
                /* PENTING: dropdownParent harus ke elemen modal yang aktif 
                agar tidak tertutup otomatis oleh focus-trap modal 
                */
                dropdownParent: $('#orderModal'), 
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') return null;

                    var found = false;
                    itemNameSelect.find('option').each(function() {
                        const optionValue = $(this).val();
                        if (optionValue && optionValue.split('|')[0].toLowerCase() === term.toLowerCase()) {
                            found = true;
                            return false;
                        }
                    });

                    return found ? null : {
                        id: term,
                        text: term + ' (Item Baru)',
                        newTag: true
                    };
                }
            });

            // 3. Event Handler Select2
            itemNameSelect.on('select2:select', function (e) {
                const data = e.params.data;
                if (data.newTag) {
                    hiddenItemName.val(data.id);
                    priceInput.val('').focus();
                    typeSelect.val('makanan').trigger('change');
                } else {
                    const parts = data.id.split('|');
                    hiddenItemName.val(parts[0]);
                    priceInput.val(parts[1]);
                    typeSelect.val(parts[2]).trigger('change');
                }
            });

            // 4. Handle Validation Errors
            @if ($errors->any())
                openOrderModal();
                const oldItemName = "{{ old('item_name') }}";
                if (oldItemName) {
                    const oldPrice = "{{ old('price') }}";
                    const oldType = "{{ old('type') }}";
                    const compositeValue = oldItemName + '|' + oldPrice + '|' + oldType;

                    if (itemNameSelect.find("option[value='" + compositeValue + "']").length) {
                        itemNameSelect.val(compositeValue).trigger('change');
                    } else {
                        var newOption = new Option(oldItemName, oldItemName, true, true);
                        itemNameSelect.append(newOption).trigger('change');
                    }
                }
            @endif

            // 5. Close on ESC
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') closeOrderModal();
            });
        });
    </script>
</x-app-layout>
