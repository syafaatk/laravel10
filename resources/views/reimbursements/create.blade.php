<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Reimbursement Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Fill the form below</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('reimbursements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- tipe -->
                         
                        <div class="form-group">
                            <label for="tipe">Tipe</label>
                            <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                                <option value="">Pilih Tipe</option>
                                <option value="1" {{ old('tipe') == '1' ? 'selected' : '' }}>Transportasi</option>
                                <option value="2" {{ (old('tipe') == '2' || request('lunch_event_id')) ? 'selected' : '' }}>Makan-makan</option>
                                <option value="3" {{ old('tipe') == '3' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                            @error('tipe')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Add this inside your form -->
                        <div class="form-group mb-3" id="lunch_event_container" style="display: none;">
                            <label for="lunch_event_id" class="form-label">Lunch Event</label>
                            <select class="form-control @error('lunch_event_id') is-invalid @enderror" id="lunch_event_id" name="lunch_event_id">
                                <option value="">-- Select Lunch Event --</option>
                                @foreach($lunchEvents as $event)
                                    <option value="{{ $event->id }}" {{ (old('lunch_event_id') == $event->id || request('lunch_event_id') == $event->id) ? 'selected' : '' }}>
                                        {{ $event->title ?? $event->created_at->format('d M Y') }}-{{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lunch_event_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required step="0.01">
                            </div>
                            @error('amount')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="attachment">Attachment Receipt, Nota</label>
                            <input type="file" name="attachment" id="attachment" class="form-control-file @error('attachment') is-invalid @enderror">
                            @error('attachment')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="attachment_note">Foto Bukti</label>
                            <input type="file" name="attachment_note" id="attachment_note" class="form-control-file @error('attachment_note') is-invalid @enderror">
                            @error('attachment_note')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>


                        <button type="submit" class="btn btn-primary" style="background-color: green; color: white;">
                            {{ __('Submit Request') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Add this script at the bottom of the view -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSelect = document.querySelector('select[name="tipe"]'); // Ensure your type select has name="tipe"
        const lunchEventContainer = document.getElementById('lunch_event_container');
        
        function toggleLunchEvent() {
            // Assuming value '2' is for "makan-makan"
            if (tipeSelect.value == '2') {
                lunchEventContainer.style.display = 'block';
            } else {
                lunchEventContainer.style.display = 'none';
                document.getElementById('lunch_event_id').value = ''; // Reset selection
            }
        }

        if(tipeSelect) {
            tipeSelect.addEventListener('change', toggleLunchEvent);
            toggleLunchEvent(); // Run on load to set initial state
        }
    });
</script>
</x-app-layout>