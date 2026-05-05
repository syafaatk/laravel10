<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if (Gate::allows('view-admin-reports'))
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Filter Pengajuan Lembur</h3>
                    <form action="{{ route('lembur.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Semua Karyawan</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Filter</button>
                            <a href="{{ route('lembur.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Pengajuan Lembur</h3>
                        <a href="{{ route('lembur.create') }}" class="btn btn-primary">Ajukan Lembur</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="table table-bordered" id="lembur-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Durasi (Jam)</th>
                                    <th>Keterangan</th>
                                    <th>Uang Lembur</th>
                                    <th>Approver</th>
                                    <th>Status</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lemburs as $lembur)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lembur->user->name }}</td>
                                        <td>{{ $lembur->tanggal->format('d-m-Y') }}</td>
                                        <td>{{ ucfirst($lembur->jenis) }}</td>
                                        <td>{{ $lembur->jam_mulai->format('H:i') }}</td>
                                        <td>{{ $lembur->jam_selesai->format('H:i') }}</td>
                                        <td>{{ $lembur->durasi_jam }}</td>
                                        <td>{{ $lembur->keterangan }}</td>
                                        <td>{{ number_format($lembur->estimasi_uang_lembur, 0, ',', '.') }}</td>
                                        @if ($lembur->approver)
                                            <td>
                                                @php
                                                    $approvers = [
                                                        '9520131577' => 'FITHRI HALIM AHMAD',
                                                        '8913230864' => 'DEDEK APRIYANI',
                                                        '8916131158' => 'ARYA REZA NUGRAHA',
                                                        '8520131736' => 'ASEP MARYANA',
                                                        '9824132111' => 'ZULFIKAR MURAKABIMAN',
                                                    ];
                                                @endphp
                                                {{ $approvers[$lembur->approver] ?? $lembur->approver }}
                                            </td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>
                                            <span class="badge {{ 
                                                $lembur->status == 'pending' ? 'bg-warning' : 
                                                ($lembur->status == 'approved' ? 'bg-success' : 'bg-danger') 
                                            }}">
                                                {{ ucfirst($lembur->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $lembur->approved_by ? $lembur->approved->name : '-' }}</td>
                                        <td>
                                            <a href="{{ route('lembur.show', $lembur->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                            @if (Auth::id() === $lembur->user_id && $lembur->status === 'pending')
                                                <a href="{{ route('lembur.edit', $lembur->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('lembur.destroy', $lembur->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan lembur ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm bg-danger">Hapus</button>
                                                </form>
                                            @endif
                                            @can('approve-lembur')
                                                @if ($lembur->status === 'pending')
                                                    <form action="{{ route('lembur.approve', $lembur->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm bg-success">Setujui</button>
                                                    </form>
                                                    <form action="{{ route('lembur.reject', $lembur->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-danger btn-sm bg-danger">Tolak</button>
                                                    </form>
                                                @endif
                                                <!-- Revisi oleh user jika sudah di approve-->
                                                @if ($lembur->status === 'approved')
                                                    <form action="{{ route('lembur.revisi', $lembur->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-warning btn-sm bg-warning">Revisi</button>
                                                    </form>
                                                @endif
                                            @endcan
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#lembur-table').DataTable({
            responsive: true,
            autoWidth: false,
            searching: true,
            paging: true,
        });
    });
</script>
</x-app-layout>