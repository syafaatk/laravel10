
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Reimbursement Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Update the form below</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('reimbursements.update', $reimbursement) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $reimbursement->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipe">Tipe</label>
                            <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                                <option value="">Pilih Tipe</option>
                                <option value="1" {{ old('tipe', $reimbursement->tipe) == '1' ? 'selected' : '' }}>Transportasi</option>
                                <option value="2" {{ old('tipe', $reimbursement->tipe) == '2' ? 'selected' : '' }}>Makan-makan</option>
                                <option value="3" {{ old('tipe', $reimbursement->tipe) == '3' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                            @error('tipe')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3" id="lunch_event_container" style="{{ old('tipe', $reimbursement->tipe) == '2' ? '' : 'display: none;' }}">
                            <label for="lunch_event_id" class="form-label">Lunch Event</label>
                            <select class="form-control @error('lunch_event_id') is-invalid @enderror" id="lunch_event_id" name="lunch_event_id">
                                <option value="">-- Select Lunch Event --</option>
                                @foreach($lunchEvents as $event)
                                    <option value="{{ $event->id }}" {{ old('lunch_event_id', $reimbursement->lunch_event_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->title ?? $event->created_at->format('d M Y') }}-{{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lunch_event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $reimbursement->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $reimbursement->amount) }}" required step="0.01">
                            </div>
                            @error('amount')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="receipt">Receipt (Leave blank to keep existing)</label>
                            <input type="file" name="receipt" id="receipt" class="form-control @error('receipt') is-invalid @enderror">
                            @error('receipt')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Reimbursement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>