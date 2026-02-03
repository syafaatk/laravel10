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
                    <table class="table table-bordered" id="laporanReimbursementsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporanReimbursements as $index => $laporan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $laporan->user->name }}</td>
                                    <td>{{ number_format($laporan->amount, 2) }}</td>
                                    <!-- status dibuat badge -->
                                    <td>
                                        <span class="badge bg-{{ $laporan->status == 'approved' ? 'success' : ($laporan->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($laporan->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $laporan->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.laporan-reimbursements.show', $laporan->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.laporan-reimbursements.edit', $laporan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.laporan-reimbursements.destroy', $laporan->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm bg-danger" onclick="return confirm('Are you sure you want to delete this report?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $laporanReimbursements->links() }}
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#laporanReimbursementsTable').DataTable({
                responsive: true,
                paging: false,
                info: false,
                searching: true,
                order: [[4, 'desc']]
            });
        });
    </script>
    @endpush
</x-app-layout>