<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Order for Event') }}: {{ $lunchEventUserOrder->lunchEvent->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w mx-auto sm:px-6 lg:px-8">
            {{-- Main Two-Column Layout (3/5 for Order, 2/5 for Menu) --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- LEFT COLUMN: Order Form & Details --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                        <div class="p-6 bg-white border-b border-gray-200">
                            
                            {{-- Header Pesanan --}}
                            <h3 class="text-xl font-bold mb-4 text-indigo-700">
                                Order for: {{ Auth::user()->name }} - {{ $lunchEventUserOrder->lunchEvent->name }} ({{ $lunchEventUserOrder->lunchEvent->restaurant->name }})
                            </h3>
                            <p class="mb-6 text-lg font-semibold text-gray-700">
                                Description: {{ $lunchEventUserOrder->lunchEvent->description }} - {{ $lunchEventUserOrder->lunchEvent->event_date }}
                            </p>
                            <p class="mb-6 text-xl font-semibold text-green-600">
                                Current Total: Rp{{ number_format($lunchEventUserOrder->total_price, 0, ',', '.') }}
                            </p>

                            <h4 class="text-lg font-semibold mb-4 border-b pb-2 text-gray-700">Add New Menu Item</h4>
                            
                            {{-- Form Tambah Item --}}
                            <form method="POST" action="{{ route('user-order-details.store', $lunchEventUserOrder->id) }}">
                                @csrf
                                
                                {{-- Two columns for form inputs --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    
                                    <!-- Menu Item Name -->
                                    <div>
                                        <x-input-label for="item_name" :value="__('Menu Item Name')" />
                                        <x-text-input id="item_name" class="block mt-1 w-full" type="text" name="item_name" :value="old('item_name')" required autofocus />
                                        <x-input-error :messages="$errors->get('item_name')" class="mt-2" />
                                    </div>
                                    
                                    <!-- Type -->
                                    <div>
                                        <x-input-label for="type" :value="__('Type')" />
                                        <select name="type" id="type"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('type') border-red-500 @enderror"
                                            required>
                                            <option value="makanan" {{ old('type') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                            <option value="minuman" {{ old('type') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                    </div>

                                    <!-- Quantity -->
                                    <div>
                                        <x-input-label for="quantity" :value="__('Quantity')" />
                                        <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', 1)" required min="1" />
                                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                    </div>

                                    <!-- Price Per Item -->
                                    <div>
                                        <x-input-label for="price" :value="__('Price Per Item (Rp)')" />
                                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="1" name="price" :value="old('price')" required min="0" />
                                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                    </div>
                                    <!-- notes -->
                                     
                                    <div>
                                        <x-input-label for="notes" :value="__('Notes')" />
                                        <select name="notes" id="notes"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror"
                                            required>
                                            <option value="ditempat" {{ old('notes') == 'ditempat' ? 'selected' : '' }}>Makan ditempat</option>
                                            <option value="bungkus" {{ old('notes') == 'bungkus' ? 'selected' : '' }}>Bungkus</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-end mt-6 space-x-3">
                                    <a href="{{ route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                        {{ __('View Order Details') }}
                                    </a>
                                    <x-primary-button class="ml-4 px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-xs">
                                        {{ __('Add Item to Order') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Current Order Details Table --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-xl font-bold mt-2 mb-4 text-indigo-700 border-b pb-2">Current Order Details:</h3>
                            
                            @if ($lunchEventUserOrder->orderDetails->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Menu Item
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Type
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Qty
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Price/Item
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Subtotal
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Notes
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($lunchEventUserOrder->orderDetails as $detail)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $detail->item_name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                                        {{ $detail->type }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $detail->quantity }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                        Rp{{ number_format($detail->price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-right">
                                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                                        {{ $detail->notes }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                        <form action="{{ route('user-order-details.destroy', $detail->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                                title="Delete Item"
                                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 6h6v10H7V6z" clip-rule="evenodd" />
                                                                </svg>
                                                                <span class="sr-only">Delete</span>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No menu items added yet. Use the form above to add your first item.</p>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- RIGHT COLUMN: Restaurant Menu --}}
                <div class="lg:col-span-3">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="max-height: 1000px; overflow-y: auto;">
                        <div class="p-6 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-xl font-bold mb-4 text-indigo-700 border-b pb-2">Restaurant Menu:</h3>
                            
                            @if ($lunchEventUserOrder->lunchEvent->restaurant)
                                <h4 class="text-lg font-semibold mb-3 text-gray-800">{{ $lunchEventUserOrder->lunchEvent->restaurant->name }} Menu</h4>
                                <p class="text-sm text-gray-600 mb-4">Click on the image or PDF icon to view the full menu.</p>
                                
                                <div class="grid grid-cols-1 gap-4" style="max-height: 800px; overflow-y: auto;">
                                    @for ($i = 1; $i <= 7; $i++)
                                        @php
                                            $menuImage = 'menu_' . $i;
                                        @endphp
                                        @if ($lunchEventUserOrder->lunchEvent->restaurant->$menuImage)
                                            <div class="border rounded-lg shadow-sm p-3 bg-white hover:shadow-md transition">
                                                <p class="font-medium text-sm text-gray-700 mb-2">Menu Asset {{ $i }}:</p>
                                                
                                                @php
                                                    $filePath = $lunchEventUserOrder->lunchEvent->restaurant->$menuImage;
                                                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                                    $fileUrl = asset('storage/restaurants/' . $filePath);
                                                @endphp

                                                @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                                    <a href="{{ $fileUrl }}" target="_blank" class="block group relative">
                                                        <img src="{{ $fileUrl }}" alt="Menu {{ $i }}" class="w-full h-40 object-cover rounded-md mb-2 transition duration-300 group-hover:opacity-75">
                                                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 rounded-md">
                                                            <span class="text-white font-bold text-sm">View Image</span>
                                                        </div>
                                                    </a>
                                                @elseif ($fileExtension == 'pdf')
                                                <div class="border border-gray-300 rounded-md overflow-hidden" style="height: 700px;">
                                                    <iframe 
                                                        src="{{ $fileUrl }}" 
                                                        width="100%" 
                                                        height="100%" 
                                                        style="border: none;"
                                                        title="Inline PDF Document">
                                                        
                                                        <!-- Fallback content for older browsers -->
                                                        <p>Your browser doesn't support embedded PDFs. Please <a href="{{ asset('assets/document.pdf') }}" download>download the PDF</a> to view it.</p>
                                                    </iframe>
                                                </div>
                                                @else
                                                    <p class="text-gray-500 italic text-sm p-4 bg-gray-100 rounded-md">Unsupported file type for Menu {{ $i }}. ({{ strtoupper($fileExtension) }})</p>
                                                @endif
                                            </div>
                                        @endif
                                    @endfor
                                </div>

                                {{-- If no menus are available, show a message --}}
                                @if (!$lunchEventUserOrder->lunchEvent->restaurant->menu_1 && !$lunchEventUserOrder->lunchEvent->restaurant->menu_2 && 
                                    !$lunchEventUserOrder->lunchEvent->restaurant->menu_3 && !$lunchEventUserOrder->lunchEvent->restaurant->menu_4 &&
                                    !$lunchEventUserOrder->lunchEvent->restaurant->menu_5 && !$lunchEventUserOrder->lunchEvent->restaurant->menu_6 &&
                                    !$lunchEventUserOrder->lunchEvent->restaurant->menu_7)
                                    <p class="text-gray-500 italic mt-4">This restaurant has no menu assets uploaded.</p>
                                @endif
                                
                            @else
                                <p class="text-gray-500 italic">No restaurant information available to display the menu.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>