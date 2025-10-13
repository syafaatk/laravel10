<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reimbursement Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Details for: {{ $reimbursement->title }}</span>
                    <a href="{{ route('reimbursements.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Title:</strong> {{ $reimbursement->title }}</p>
                            <p><strong>Requested by:</strong> {{ $reimbursement->user->name }}</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($reimbursement->amount, 2, ',', '.') }}</p>
                            <p><strong>Date Submitted:</strong> {{ $reimbursement->created_at->format('d F Y, H:i') }}</p>
                            <p><strong>Description:</strong></p>
                            <p>{{ $reimbursement->description }}</p>

                            @if ($reimbursement->attachment)
                                <p><strong>Receipt:</strong></p>
                                <a href="{{ route('reimbursements.download', $reimbursement) }}" class="btn btn-link">Download Receipt</a>
                                <img src="{{ asset('storage/' . $reimbursement->attachment) }}" alt="Receipt" class="img-fluid mt-2">
                                @if ($reimbursement->attachment_note)
                                    <p class="mt-3"><strong>Foto Bukti:</strong></p>
                                    <a href="{{ route('reimbursements.downloadNote', $reimbursement) }}" class="btn btn-link">Download Foto Bukti</a>
                                    <img src="{{ asset('storage/' . $reimbursement->attachment_note) }}" alt="Foto Bukti" class="img-fluid mt-2">
                                @endif
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <h5>Status</h5>
                                <hr>
                                <p>
                                    @if ($reimbursement->status == 'pending')
                                        <span class="badge badge-warning" style="font-size: 1rem;">Pending</span>
                                    @elseif ($reimbursement->status == 'approved')
                                        <span class="badge badge-success" style="font-size: 1rem;">Approved</span>
                                    @else
                                        <span class="badge badge-danger" style="font-size: 1rem;">Rejected</span>
                                    @endif
                                </p>
                                @if ($reimbursement->processed_at)
                                    <p class="mt-3">
                                        <strong>Processed by:</strong> {{ $reimbursement->processor->name ?? 'N/A' }}<br>
                                        <strong>Processed at:</strong> {{ $reimbursement->processed_at }}
                                    </p>
                                @endif

                                @if (Auth::user()->hasRole('admin') && $reimbursement->status == 'pending')
                                    <hr>
                                    <div class="d-flex">
                                        <form action="{{ route('reimbursements.approve', $reimbursement) }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" style="background-color: green; color: white;">Diajukan ke QT</button>
                                        </form>
                                        <form action="{{ route('reimbursements.reject', $reimbursement) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger" style="background-color: red; color: white;">Selesai</button>
                                        </form>
                                    </div>
                                @endif
                                @if (Auth::user()->hasRole('admin') && $reimbursement->status != 'pending')
                                    <hr>
                                    <div class="d-flex">
                                        <form action="{{ route('reimbursements.pending', $reimbursement) }}" method="POST" class="d-inline mr-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning" style="background-color: orange; color: white;">Pengajuan</button>
                                        </form>
                                        <form action="{{ route('reimbursements.reject', $reimbursement) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger" style="background-color: red; color: white;">Selesai</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>