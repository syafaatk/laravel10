<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Pengunduran Diri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Daftar Pengajuan Pengunduran Diri</span>
                        <a href="{{ route('pengunduran.create') }}" class="btn btn-primary">Buat Pengajuan</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table id="pengunduran-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                @if (Auth::user()->hasRole('admin'))
                                    <th>Pemohon</th>
                                @endif
                                <th>Tanggal Request</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengundurans as $pengunduran)
                                <tr>
                                    @if (Auth::user()->hasRole('admin'))
                                        <td>{{ $pengunduran->user->name }}</td>
                                    @endif
                                    <td>{{ $pengunduran->requested_date->format('d M Y') }}</td>
                                    <td>{{ $pengunduran->reason }}</td>
                                    <td>
                                        @if ($pengunduran->status == 'pending')
                                            <span class="badge text-bg-warning">Pending on Leader</span>
                                        @elseif ($pengunduran->status == 'approved')
                                            <span class="badge text-bg-success">Approved</span>
                                        @else
                                            <span class="badge text-bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pengunduran.show', $pengunduran) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <!-- print if approved -->
                                        @if ($pengunduran->status == 'approved')
                                            <a href="{{ route('pengunduran.print', $pengunduran) }}" class="btn btn-sm btn-secondary" target="_blank">Print</a>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') && $pengunduran->status == 'pending')
                                            <form action="{{ route('pengunduran.approve', $pengunduran) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" style="background-color: green; color: white;">Approve</button>
                                            </form>
                                            <form action="{{ route('pengunduran.reject', $pengunduran) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" style="background-color: red; color: white;">Reject</button>
                                            </form>
                                        @endif
                                        @if ($pengunduran->status != 'approved')
                                            <a href="{{ route('pengunduran.destroy', $pengunduran) }}" class="btn btn-sm btn-danger" target="_blank">Delete</a>
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

    @push('scripts')
    <script>
        $(document).ready(function() { $('#pengunduran-table').DataTable(); });
    </script>
    @endpush
</x-app-layout>