<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Pengajuan Lembur</h3>

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

                    <form action="{{ route('lembur.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="tanggal" class="form-label">Tanggal Lembur</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="jenis" class="form-label">Jenis Lembur</label>
                            <select class="form-control" id="jenis" name="jenis" required>
                                <option value="weekday" {{ old('jenis') == 'weekday' ? 'selected' : '' }}>Hari Kerja</option>
                                <option value="weekend" {{ old('jenis') == 'weekend' ? 'selected' : '' }}>Akhir Pekan</option>
                                <option value="holiday" {{ old('jenis') == 'holiday' ? 'selected' : '' }}>Hari Libur Nasional</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="form-label">Keterangan Tambahan (Opsional)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        </div>
                        <!-- approver select option -->
                         
                        <div class="mb-4">
                            <label for="approver" class="form-label">Pilih Approver</label>
                            <select class="form-control" id="approver" name="approver" required>
                                <option value="">Pilih Approver</option>
                                <option value="9520131577">FITHRI HALIM AHMAD</option>
                                <option value="8913230864">DEDEK APRIYANI</option>
                                <option value="8916131158">ARYA REZA NUGRAHA</option>
                                <option value="8520131736">ASEP MARYANA</option>
                                <option value="9824132111">ZULFIKAR MURAKABIMAN</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Uraian Pekerjaan Lembur</label>
                            <div id="uraian-pekerjaan-container">
                                @if (old('uraian_pekerjaan'))
                                    @foreach (old('uraian_pekerjaan') as $index => $uraian)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="uraian_pekerjaan[]" value="{{ $uraian }}" placeholder="Uraian pekerjaan" required>
                                            <button type="button" class="btn btn-danger remove-uraian">Hapus</button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="uraian_pekerjaan[]" placeholder="Uraian pekerjaan" required>
                                        <button type="button" class="btn btn-danger remove-uraian bg-danger">Hapus</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-2 bg-secondary" id="add-uraian">Tambah Uraian Pekerjaan</button>
                        </div>

                        <button type="submit" class="btn btn-primary bg-primary">Ajukan Lembur</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        // if weekend or holiday jam_mulai 7:30 AM dan jam_selesai 4:30 PM dan durasi 8 jam
        // if weekday jam_mulai 4:30 PM dan jam_selesai 8:30 PM dan durasi 4 jam
        $(document).ready(function() {
            function setTimeDefaults() {
                const jenisLembur = $('#jenis').val();
                if (jenisLembur === 'weekend' || jenisLembur === 'holiday') {
                    $('#jam_mulai').val('07:30');
                    $('#jam_selesai').val('16:30');
                } else if (jenisLembur === 'weekday') {
                    $('#jam_mulai').val('16:30');
                    $('#jam_selesai').val('20:30');
                }
            }

            // Set defaults on page load
            setTimeDefaults();

            // Set defaults when jenis lembur changes
            $('#jenis').change(function() {
                setTimeDefaults();
            });

            $('#add-uraian').click(function() {
                $('#uraian-pekerjaan-container').append(`
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="uraian_pekerjaan[]" placeholder="Uraian pekerjaan" required>
                        <button type="button" class="btn btn-danger remove-uraian bg-danger">Hapus</button>
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