<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Reimbursement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Header / Summary --}}
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $laporanReimbursement->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $laporanReimbursement->description ?? '' }}</p>
                            <div class="text-sm text-gray-500 mt-2">
                                <span class="mr-3"><strong>Start:</strong> {{ $laporanReimbursement->start_date }}</span>
                                <span class="mr-3"><strong>End:</strong> {{ $laporanReimbursement->end_date }}</span>
                                <span class="mr-3"><strong>Created:</strong> {{ $laporanReimbursement->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-gray-500">Laporan Owner</div>
                            <div class="font-medium text-gray-900">{{ optional($laporanReimbursement->user)->name ?? '-' }}</div>

                            @php
                                $items = $laporanReimbursement->reimbursements ?? collect();
                                $totalAmount = $items->sum('amount');
                            @endphp

                            <div class="mt-3">
                                <div class="text-xs text-gray-500">Total Reimbursements</div>
                                <div class="text-2xl font-bold text-green-600">Rp{{ number_format($totalAmount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Table of reimbursements --}}
                    <div class="w-full overflow-x-auto">
                        <table id="reimbursements-table" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($items as $i => $reimbursement)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-800">
                                            {{ $reimbursement->title ?? '-' }}
                                            @if($reimbursement->note)
                                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($reimbursement->note, 80) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ optional($reimbursement->user)->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-800">Rp{{ number_format($reimbursement->amount ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            @php
                                                $status = strtolower($reimbursement->status ?? '');
                                                $badge = 'bg-gray-100 text-gray-800';
                                                if ($status === 'pending') $badge = 'bg-yellow-100 text-yellow-800';
                                                if ($status === 'approved') $badge = 'bg-green-100 text-green-800';
                                                if ($status === 'rejected') $badge = 'bg-red-100 text-red-800';
                                                if ($status === 'done') $badge = 'bg-blue-100 text-blue-800';
                                            @endphp
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $badge }}">
                                                {{ ucfirst($reimbursement->status ?? '-') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ optional($reimbursement->created_at)->format('d M Y') ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            <a href="{{ route('reimbursements.show', $reimbursement) }}" class="inline-flex items-center px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">View</a>

                                            @can('update', $reimbursement)
                                                <a href="{{ route('reimbursements.edit', $reimbursement) }}" class="inline-flex items-center px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600 ml-2">Edit</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">No reimbursements linked to this report.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Back button --}}
                    <div class="mt-6">
                        <a href="{{ route('admin.laporan-reimbursements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 text-sm">
                            {{ __('Back to List') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // init DataTable if available
            if (typeof $ !== 'undefined' && $.fn.dataTable) {
                $('#reimbursements-table').DataTable({
                    responsive: true,
                    paging: true,
                    pageLength: 10,
                    ordering: true,
                    order: [[ 5, "desc" ]], // order by submitted date desc (zero-based index)
                    columnDefs: [
                        { orderable: false, targets: [6] } // actions not orderable
                    ],
                    language: {
                        emptyTable: "No reimbursements available"
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
