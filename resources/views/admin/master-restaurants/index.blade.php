<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Restaurant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        @if(Auth::user()->hasRole('admin')) 
                        <a href="{{ route('master-restaurants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Add New Restaurant') }}
                        </a>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @elseif (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Restaurant Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Address
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Phone
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Lokasi
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masterRestaurants as $restaurant)
                                    <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="ps-3">
                                                <div class="font-semibold text-base text-gray-800">{{ $restaurant->name }}</div>
                                                <div class="font-normal text-gray-500">{{ $restaurant->email }}</div>
                                            </div>
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $restaurant->address }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                {{ $restaurant->phone_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- link to google maps -->
                                             <a href="https://www.google.com/maps/search/?api=1&query={{ $restaurant->latitude }},{{ $restaurant->longitude }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View on Map</a>
                                            <br>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- show -->
                                            <a href="{{ route('master-restaurants.show', $restaurant->id) }}" class="btn btn-sm btn-info bg-green-600 hover:bg-green-700 text-white font-medium text-green-600 dark:text-green-500 hover:underline">Show</a>
                                            <a href="{{ route('master-restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-primary bg-blue-600 hover:bg-blue-700 text-white font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            <!-- delete -->
                                            <form action="{{ route('master-restaurants.destroy', $restaurant->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this restaurant?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger bg-red-600 hover:bg-red-700 text-white font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    @endpush
</x-app-layout>
