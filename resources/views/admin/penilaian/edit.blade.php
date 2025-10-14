<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Penilaian Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Edit Penilaian untuk {{ $penilaian->user->name }}</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.penilaian.update', $penilaian->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="user_id">Pegawai</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $penilaian->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="review_date">Tanggal Penilaian</label>
                            <input type="date" name="review_date" id="review_date" class="form-control @error('review_date') is-invalid @enderror" value="{{ old('review_date', $penilaian->review_date->format('Y-m-d')) }}" required>
                            @error('review_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="period_start">Periode Mulai</label>
                            <input type="date" name="period_start" id="period_start" class="form-control @error('period_start') is-invalid @enderror" value="{{ old('period_start', $penilaian->period_start->format('Y-m-d')) }}" required>
                            @error('period_start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="period_end">Periode Selesai</label>
                            <input type="date" name="period_end" id="period_end" class="form-control @error('period_end') is-invalid @enderror" value="{{ old('period_end', $penilaian->period_end->format('Y-m-d')) }}" required>
                            @error('period_end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <h5 class="mt-4">Kriteria Penilaian (Skor 1-5)</h5>
                        <div class="row">
                            @foreach ($criteria as $key => $label)
                            <div class="col-md-6 form-group">
                                <label for="scores_{{ $key }}">{{ $label }}</label>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('scores.'.$key) is-invalid @enderror" type="radio" name="scores[{{ $key }}]" id="scores_{{ $key }}_{{ $i }}" value="{{ $i }}" {{ old('scores.'.$key, $penilaian->getScoreForCriteria($key)) == $i ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="scores_{{ $key }}_{{ $i }}">{{ $i }}</label>
                                        </div>
                                    @endfor
                                </div>
                                @error('scores.'.$key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @endforeach
                        </div>

                        <hr>    
                        <div class="form-group">
                            <label for="strengths">Kelebihan / Poin Positif</label>
                            <textarea name="strengths" id="strengths" class="form-control">{{ old('strengths', $penilaian->strengths) }}</textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="weaknesses">Area yang Perlu Ditingkatkan</label>
                            <textarea name="weaknesses" id="weaknesses" class="form-control">{{ old('weaknesses', $penilaian->weaknesses) }}</textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="feedback">Feedback Tambahan</label>
                            <textarea name="feedback" id="feedback" class="form-control">{{ old('feedback', $penilaian->feedback) }}</textarea>
                        </div>
                        <x-primary-button class="mt-4">
                            {{ __('Update Penilaian') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>