<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
</x-app-layout>