<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asset Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $masterAsset->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $masterAsset->type }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Serial Number:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $masterAsset->serial_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Purchase Date:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $masterAsset->purchase_date ? $masterAsset->purchase_date->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Purchase Price:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $masterAsset->purchase_price ? 'Rp ' . number_format($masterAsset->purchase_price, 2, ',', '.') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Status:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $masterAsset->status == 'available' ? 'bg-green-100 text-green-800' : ($masterAsset->status == 'assigned' ? 'bg-blue-100 text-blue-800' : ($masterAsset->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($masterAsset->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Assigned To:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $masterAsset->assignedToUser ? $masterAsset->assignedToUser->name : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ __('Assigned Date:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $masterAsset->assigned_date ? $masterAsset->assigned_date->format('d M Y') : '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-700">{{ __('Notes:') }}</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $masterAsset->notes ?: '-' }}</p>
                        </div>
                        @if ($masterAsset->image)
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-700">{{ __('Asset Image:') }}</p>
                                <img src="{{ asset('storage/' . $masterAsset->image) }}" alt="{{ $masterAsset->name }}" class="mt-2 max-w-xs h-auto rounded-md shadow-md">
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.master-assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>