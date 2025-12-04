<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lunch Events') }}
        </h2>
    </x-slot>

    {{-- DataTables CSS (CDN) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Lunch Events</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Pantau event makan siang, pesanan, dan ringkasan transaksi.
                        </p>
                    </div>

                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('lunch-events.create') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                            </svg>
                            Create New Lunch Event
                        </a>
                    @endif
                </div>

                <div class="px-6 pt-4">
                    {{-- Alert --}}
                    @if (session('success'))
                        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            <strong class="font-semibold">Success!</strong>
                            <span class="ml-1">{{ session('success') }}</span>
                        </div>
                    @elseif (session('error'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <strong class="font-semibold">Error!</strong>
                            <span class="ml-1">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                <div class="px-6 pb-6">
                    <div class="overflow-x-auto rounded-xl border border-gray-100 pb-4 px-4 py-4">
                        <table id="lunchEventsTable" class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-[11px] uppercase text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Title</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Time</th>
                                    <th class="px-4 py-3 text-left">Location</th>
                                    <th class="px-4 py-3 text-left">Total Orders</th>
                                    <th class="px-4 py-3 text-left">Total Items</th>
                                    <th class="px-4 py-3 text-left">Total Revenue</th>
                                    <th class="px-4 py-3 text-left">Avg Order Value</th>
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($lunchEvents as $event)
                                    @php
                                        $eventDate = \Carbon\Carbon::parse($event->event_date);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        {{-- Title --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-900">{{ $event->name }}</span>
                                                @if(isset($event->status))
                                                    <span class="mt-1 inline-flex w-fit items-center rounded-full px-2 py-0.5 text-[10px] 
                                                        @if($event->status === 'scheduled') bg-emerald-50 text-emerald-700
                                                        @elseif($event->status === 'completed') bg-gray-100 text-gray-700
                                                        @else bg-red-50 text-red-700 @endif">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Date (pakai data-order untuk sort) --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="text-sm text-gray-900"
                                                data-order="{{ $eventDate->timestamp }}"
                                            >
                                                {{ $eventDate->format('M d, Y') }}
                                            </span>
                                        </td>

                                        {{-- Time (sementara fixed) --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">
                                                5:00 PM
                                            </span>
                                        </td>

                                        {{-- Location --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">{{ $event->restaurant->name }}</span>
                                                <span class="mx-1 text-gray-400">•</span>
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $event->restaurant->latitude }},{{ $event->restaurant->longitude }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6c0 4.418 6 10 6 10s6-5.582 6-10a6 6 0 00-6-6zm0 3a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                                                    </svg>
                                                    View on Map
                                                </a>
                                            </div>
                                        </td>

                                        {{-- Total Orders --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm text-gray-800">
                                                {{ $event->orders_count }} orang
                                            </span>
                                        </td>

                                        {{-- Total Items --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm text-gray-800">
                                                {{ $event->total_items }}
                                            </span>
                                        </td>

                                        {{-- Total Revenue --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format($event->total_revenue, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        {{-- Avg Order Value --}}
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">
                                                Rp {{ number_format($event->avg_order_value, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                @if(Auth::user()->hasRole('admin'))
                                                    <a href="{{ route('lunch-events.show', $event->id) }}"
                                                       class="inline-flex items-center rounded-lg bg-indigo-50 px-2 py-1 text-[11px] text-indigo-700 hover:bg-indigo-100">
                                                        View
                                                    </a>
                                                    <a href="{{ route('lunch-events.edit', $event->id) }}"
                                                       class="inline-flex items-center rounded-lg bg-emerald-50 px-2 py-1 text-[11px] text-emerald-700 hover:bg-emerald-100">
                                                        Edit
                                                    </a>
                                                    <a href="{{ route('lunch-event-user-orders.create', $event->id) }}"
                                                       class="inline-flex items-center rounded-lg bg-amber-400 px-2 py-1 text-[11px] text-white hover:bg-amber-500">
                                                        Pesan
                                                    </a>
                                                    <form action="{{ route('lunch-events.destroy', $event->id) }}" method="POST" class="inline-block"
                                                          onsubmit="return confirm('Are you sure you want to delete this event?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center rounded-lg bg-red-500 px-2 py-1 text-[11px] text-white hover:bg-red-600">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('lunch-events.show', $event->id) }}"
                                                       class="inline-flex items-center rounded-lg bg-indigo-50 px-2 py-1 text-[11px] text-indigo-700 hover:bg-indigo-100">
                                                        View Details
                                                    </a>

                                                    @if($event->status == 'scheduled')
                                                        <a href="{{ route('lunch-event-user-orders.create', $event->id) }}"
                                                           class="inline-flex items-center rounded-lg bg-amber-400 px-2 py-1 text-[11px] text-white hover:bg-amber-500">
                                                            Buat Pesanan
                                                        </a>
                                                    @else
                                                        <span class="text-[11px] text-gray-400">
                                                            Order Closed
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
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

    {{-- jQuery & DataTables JS (CDN) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi DataTable dengan default sort DESC di kolom Date (index 1)
            $('#lunchEventsTable').DataTable({
                order: [[1, 'asc']],
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ events",
                }
            });
        });
    </script>
</x-app-layout>
