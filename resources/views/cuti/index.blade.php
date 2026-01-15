<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Cuti') }}
        </h2>
        <!-- filter tahun -->
        <form method="GET" action="{{ route('cuti.index') }}" class="mt-4">
            <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2">
                <label for="year" class="form-label mb-0 fw-semibold">Filter by Year:</label>
                <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
                </div>
            </div>
            
            <!-- jika admin tampilkan filter pegawai-->
            @if (Auth::user()->hasRole('admin'))
                <div class="col-md-6">
                <div class="d-flex align-items-center gap-2">
                    <label for="user_id_filter" class="form-label mb-0 fw-semibold">Filter by Employee:</label>
                    <select name="user_id_filter" id="user_id_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Employees</option>
                    @foreach ($users as $userFilter)
                        <option value="{{ $userFilter->id }}" {{ request('user_id_filter') == $userFilter->id ? 'selected' : '' }}>
                        {{ $userFilter->name }} - Cuti Approved: {{ $userFilter->cutiApproved->sum('days_requested') }} / 12 Hari
                        </option>
                    @endforeach
                    </select>
                </div>
                </div>
            @endif
            </div>
        </form>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- total cuti yang sudah diambil tahun {{ request('year') }} -->
            <div class="mb-4 p-4 bg-white shadow-sm rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Total Cuti Tahunan yang Sudah Diambil Tahun {{ request('year', date('Y')) }}:</h3>
                <p class="text-gray-700">
                    {{ $totalCutiApproved }} Hari dari 12 Hari
                </p>
            </div>
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