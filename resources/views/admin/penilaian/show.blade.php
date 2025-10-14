<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penilaian Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Penilaian untuk {{ $penilaian->user->name }}</span>
                    <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Pegawai Dinilai:</strong> {{ $penilaian->user->name }}</p>
                            <p><strong>Penilai:</strong> {{ $penilaian->reviewer->name }}</p>
                            <p><strong>Tanggal Penilaian:</strong> {{ $penilaian->review_date->format('d M Y') }}</p>
                            <p><strong>Periode Penilaian:</strong> {{ $penilaian->period_start->format('d M Y') }} - {{ $penilaian->period_end->format('d M Y') }}</p>
                            <p><strong>Skor Keseluruhan:</strong> {{ $penilaian->overall_score }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Kekuatan:</strong> {{ $penilaian->strengths }}</p>
                            <p><strong>Kelemahan:</strong> {{ $penilaian->weaknesses }}</p>
                            <p><strong>Umpan Balik:</strong> {{ $penilaian->feedback }}</p>
                        </div>
                    </div>

                    <h5 class="mt-4">Detail Skor:</h5>
                    <table class="table table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th>Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- json -->
                            @foreach ($penilaian->scores as $score)
                                <tr>
                                    <td>{{ $score['criteria_name'] }}</td>
                                    <td>{{ $score['score_value'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>