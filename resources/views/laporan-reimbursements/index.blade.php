<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Reimbursements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>List Laporan Reimbursements</span>
                </div>
                <div class="card-header">
                    <a href="{{ route('admin.laporan-reimbursements.create') }}" class="btn btn-primary">Tambah Laporan Reimbursements</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporanReimbursements as $index => $laporan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $laporan->user->name }}</td>
                                    <td>{{ number_format($laporan->total_amount, 2) }}</td>
                                    <td>{{ ucfirst($laporan->status) }}</td>
                                    <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $laporanReimbursements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>