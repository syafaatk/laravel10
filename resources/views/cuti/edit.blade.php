<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Cuti Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Edit Cuti Request</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('cuti.update', $cuti->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="master_cuti_id">Leave Type</label>
                            <select name="master_cuti_id" id="master_cuti_id" class="form-control @error('master_cuti_id') is-invalid @enderror" required>
                                <option value="">Select Leave Type</option>
                                @foreach ($masterCutis as $masterCuti)
                                    <option value="{{ $masterCuti->id }}" {{ old('master_cuti_id', $cuti->master_cuti_id) == $masterCuti->id ? 'selected' : '' }}>
                                        {{ $masterCuti->name }} (Available: {{ $masterCuti->available_days }} days)
                                    </option>
                                @endforeach
                            </select>
                            @error('master_cuti_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $cuti->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @endsection
                        </div>
                        <div class="form-group mt-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $cuti->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="reason">Reason</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason', $cuti->reason) }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <x-primary-button class="mt-4">
                            {{ __('Update Request') }}
                        </x-primary-button>
                        <a href="{{ route('cuti.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4">
                            {{ __('Cancel') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
