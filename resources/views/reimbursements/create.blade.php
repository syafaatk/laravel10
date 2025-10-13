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
                                <option value="2" {{ old('tipe') == '2' ? 'selected' : '' }}>Makan-makan</option>
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
</x-app-layout>