<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Daftar Pengajuan Cuti</span>
                        <a href="{{ route('cuti.create') }}" class="btn btn-primary">Buat Pengajuan</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table id="cuti-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                @if (Auth::user()->hasRole('admin'))
                                    <th>Pemohon</th>
                                @endif
                                <th>Jenis Cuti</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Jumlah Hari</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cutis as $cuti)
                                <tr>
                                    @if (Auth::user()->hasRole('admin'))
                                        <td>{{ $cuti->user->name }}</td>
                                    @endif
                                    <td>{{ $cuti->masterCuti->name }}</td>
                                    <td>{{ $cuti->start_date->format('d M Y') }}</td>
                                    <td>{{ $cuti->end_date->format('d M Y') }}</td>
                                    <td>{{ $cuti->days_requested }}</td>
                                    <td>
                                        @if ($cuti->status == 'pending')
                                            <span class="badge text-bg-warning">Pending on Leader</span>
                                        @elseif ($cuti->status == 'approved')
                                            <span class="badge text-bg-success">Approved</span>
                                        @else
                                            <span class="badge text-bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('cuti.show', $cuti) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <!-- print if approved -->
                                        @if ($cuti->status == 'approved')
                                            <a href="{{ route('cuti.print', $cuti) }}" class="btn btn-sm btn-secondary" target="_blank">Print</a>
                                        @endif
                                        @if (Auth::user()->hasRole('admin') && $cuti->status == 'pending')
                                            <form action="{{ route('cuti.approve', $cuti) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" style="background-color: green; color: white;">Approve</button>
                                            </form>
                                            <form action="{{ route('cuti.reject', $cuti) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" style="background-color: red; color: white;">Reject</button>
                                            </form>
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
        $(document).ready(function() { $('#cuti-table').DataTable(); });
    </script>
    @endpush
</x-app-layout>