<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reimbursements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>My Reimbursement Requests</span>
                        <a href="{{ route('reimbursements.create') }}" class="btn btn-primary">New Request</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('reimbursements.print') }}" method="POST" target="_blank">
                        @csrf
                        @if(Auth::user()->hasRole('admin'))
                        <div class="mb-3">
                            <x-primary-button>
                                {{ __('Print All Reimbursements') }}
                            </x-primary-button>
                        </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <table id="reimbursements-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Title</th>
                                <th>Type</th>
                                @if (Auth::user()->hasRole('admin'))
                                    <th>Requested By</th>
                                @endif
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Date Approval</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reimbursements as $reimbursement)
                                <tr>
                                    <td><input type="checkbox" name="reimbursement_ids[]" value="{{ $reimbursement->id }}" class="reimbursement-checkbox"></td>
                                    <td>{{ $reimbursement->title }}</td>
                                    <td>
                                        @if ($reimbursement->tipe == 1) {{ __('Transportasi') }} @elseif ($reimbursement->tipe == 2) {{ __('Makan-makan') }} @else {{ __('Lain-lain') }} @endif
                                    </td>
                                    @if (Auth::user()->hasRole('admin'))
                                        <td>{{ $reimbursement->user->name }}</td>
                                    @endif
                                    <td>Rp {{ number_format($reimbursement->amount, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($reimbursement->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($reimbursement->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $reimbursement->created_at->format('d M Y') }}</td>
                                    <td>{{ $reimbursement->processed_at?->format('d M Y') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('reimbursements.show', $reimbursement) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#reimbursements-table').DataTable({
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                }],
                'order': [
                    [1, 'asc']
                ]
            });

            $('#select-all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            $('#reimbursements-table tbody').on('change', 'input[type="checkbox"]', function() {
                if (!this.checked) {
                    var el = $('#select-all').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>