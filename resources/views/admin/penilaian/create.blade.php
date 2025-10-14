<x-app-layout>
<style>
    .form-group {
        margin-bottom: 1rem;
    }
    .form-check-label {
        margin-right: 1rem;
    }
</style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Penilaian Pegawai Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Isi Formulir Penilaian</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penilaian.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="user_id">Pegawai yang Dinilai</label>
                                <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                    <option value="">Pilih Pegawai</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="review_date">Tanggal Penilaian</label>
                                <input type="date" name="review_date" id="review_date" class="form-control @error('review_date') is-invalid @enderror" value="{{ old('review_date', now()->format('Y-m-d')) }}" required>
                                @error('review_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="period_start">Periode Mulai</label>
                                <input type="date" name="period_start" id="period_start" class="form-control @error('period_start') is-invalid @enderror" value="{{ old('period_start') }}" required>
                                @error('period_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="period_end">Periode Selesai</label>
                                <input type="date" name="period_end" id="period_end" class="form-control @error('period_end') is-invalid @enderror" value="{{ old('period_end') }}" required>
                                @error('period_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr>
                        <h5>Kriteria Penilaian (Skor 1-5)</h5>
                        <div class="row">
                            @foreach ($criteria as $key => $label)
                            <div class="col-md-6 form-group">
                                <label for="scores_{{ $key }}">{{ $label }}</label>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('scores.'.$key) is-invalid @enderror" type="radio" name="scores[{{ $key }}]" id="scores_{{ $key }}_{{ $i }}" value="{{ $i }}" {{ old('scores.'.$key, 5) == $i ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="scores_{{ $key }}_{{ $i }}">{{ $i }}</label>
                                        </div>
                                    @endfor
                                </div>
                                @error('scores.'.$key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @endforeach
                        </div>
                        <!-- kata kata otomatis untuk strengths dan weekness diambil dari nilai -->

                        <hr>
                        <div class="form-group">
                            <label for="strengths">Kelebihan / Poin Positif</label>
                            <textarea name="strengths" id="strengths" class="form-control">{{ old('strengths') }} Karyawan menunjukkan kinerja yang sangat baik dalam mencapai target.</textarea>
                        </div>

                        <div class="form-group">
                            <label for="weaknesses">Area yang Perlu Ditingkatkan</label>
                            <textarea name="weaknesses" id="weaknesses" class="form-control">{{ old('weaknesses') }} Karyawan perlu meningkatkan keterampilan komunikasi dan kolaborasi dalam tim.</textarea>
                        </div>

                        <div class="form-group">
                            <label for="feedback">Feedback & Rencana Pengembangan</label>
                            <textarea name="feedback" id="feedback" class="form-control">Dapat diperpanjang, untuk kenaikan gaji maksimal Rp. </textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Simpan Penilaian</button>
                            <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
