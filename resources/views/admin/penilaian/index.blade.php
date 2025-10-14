<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Daftar Penilaian</span>
                        <a href="{{ route('admin.penilaian.create') }}" class="btn btn-primary">Buat Penilaian Baru</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table id="penilaian-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>Tanggal Penilaian</th>
                                <th>Periode</th>
                                <th>Skor Akhir</th>
                                <th>Penilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penilaians as $penilaian)
                                <tr>
                                    <td>{{ $penilaian->user->name }}</td>
                                    <td>{{ $penilaian->review_date->format('d M Y') }}</td>
                                    <td>{{ $penilaian->period_start->format('d M Y') }} - {{ $penilaian->period_end->format('d M Y') }}</td>
                                    <td>{{ number_format($penilaian->overall_score, 2) }}</td>
                                    <td>{{ $penilaian->reviewer->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.penilaian.show', $penilaian) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('admin.penilaian.print', $penilaian) }}" class="btn btn-sm btn-success">Cetak</a>
                                        <a href="{{ route('admin.penilaian.edit', $penilaian) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.penilaian.destroy', $penilaian) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" style="background-color: red; color: white;">Hapus</button>
                                        </form>
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
        $(document).ready(function() { $('#penilaian-table').DataTable(); });
    </script>
    @endpush
</x-app-layout>
