
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengajuan Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Edit Pengajuan Lembur</h3>

                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('lembur.update', $lembur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="tanggal" class="form-label">Tanggal Lembur</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $lembur->tanggal->format('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="jenis" class="form-label">Jenis Lembur</label>
                            <select class="form-control" id="jenis" name="jenis" required>
                                <option value="weekday" {{ old('jenis', $lembur->jenis) == 'weekday' ? 'selected' : '' }}>Hari Kerja</option>
                                <option value="weekend" {{ old('jenis', $lembur->jenis) == 'weekend' ? 'selected' : '' }}>Akhir Pekan</option>
                                <option value="holiday" {{ old('jenis', $lembur->jenis) == 'holiday' ? 'selected' : '' }}>Hari Libur Nasional</option>
                            </select>
                            
                        </div>

                        <div class="mb-4">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', $lembur->jam_mulai->format('H:i')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', $lembur->jam_selesai->format('H:i')) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="form-label">Keterangan Tambahan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $lembur->keterangan) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Uraian Pekerjaan Lembur</label>
                            <div id="uraian-pekerjaan-container">
                                @if (old('uraian_pekerjaan'))
                                    @foreach (old('uraian_pekerjaan') as $index => $uraian)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="uraian_pekerjaan[]" value="{{ $uraian }}" placeholder="Uraian pekerjaan" required>
                                            <button type="button" class="btn btn-danger remove-uraian bg-danger">Hapus</button>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach ($lembur->detailLemburs as $detail)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="uraian_pekerjaan[]" value="{{ $detail->uraian_pekerjaan }}" placeholder="Uraian pekerjaan" required>
                                            <button type="button" class="btn btn-danger remove-uraian bg-danger">Hapus</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-2 bg-secondary" id="add-uraian">Tambah Uraian Pekerjaan</button>
                        </div>

                        <button type="submit" class="btn btn-primary bg-primary">Update Pengajuan Lembur</button>
                        <a href="{{ route('lembur.index') }}" class="btn btn-secondary bg-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#add-uraian').click(function() {
                $('#uraian-pekerjaan-container').append(`
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="uraian_pekerjaan[]" placeholder="Uraian pekerjaan" required>
                        <button type="button" class="btn btn-danger remove-uraian">Hapus</button>
                    </div>
                `);
            });

            $(document).on('click', '.remove-uraian', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
    @endpush
</x-app-layout>

