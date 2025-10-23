<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lunch Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('lunch-events.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Lunch Event
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
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($lunchEvents as $event)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">5:00 PM</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->restaurant->name }} - 
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $event->restaurant->latitude }},{{ $event->restaurant->longitude }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View on Map</a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <!-- jika admin -->
                                             
                                            @if(Auth::user()->hasRole('admin'))
                                            <a href="{{ route('lunch-events.show', $event->id) }}" class="btn btn-primary text-white hover:text-indigo-900 py-1 px-2 rounded text-xs">View</a>
                                            <a href="{{ route('lunch-events.edit', $event->id) }}" class="btn btn-primary text-white hover:text-green-900 py-1 px-2 rounded text-xs">Edit</a>
                                            <a href="{{ route('lunch-event-user-orders.create', $event->id) }}" class="btn btn-primary bg-yellow-500 hover:bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">Buat Pesanan</a>
                                            <form action="{{ route('lunch-events.destroy', $event->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white bg-red-500 hover:text-red-600 hover:text-red-900 py-1 px-2 rounded text-xs" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                                <!-- jika user biasa -->
                                            </form>
                                            @else
                                            <!-- jika user biasa -->
                                            <a href="{{ route('lunch-events.show', $event->id) }}" class="btn btn-primary text-white hover:text-indigo-900 py-1 px-2 rounded text-xs">View Details</a>
                                                @if($event->status == 'scheduled')
                                                <a href="{{ route('lunch-event-user-orders.create', $event->id) }}" class="btn btn-primary bg-yellow-500 hover:bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">Buat Pesanan</a>
                                                @endif
                                            @endif
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
</x-app-layout>