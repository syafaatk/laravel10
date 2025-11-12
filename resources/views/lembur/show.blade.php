<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan Lembur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Detail Pengajuan Lembur</h3>
                    
                    {{-- Menggunakan Grid untuk tata letak 2 kolom pada layar medium ke atas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mb-6">
                        
                        {{-- Kolom Kiri --}}
                        <div>
                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Nama Karyawan:</label>
                                <p class="text-gray-900 text-lg font-medium">{{ $lembur->user->name }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Tanggal Lembur:</label>
                                <p class="text-gray-900">{{ $lembur->tanggal->format('d F Y') }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Jenis Lembur:</label>
                                <p class="text-gray-900">{{ ucfirst($lembur->jenis) }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Durasi (Jam):</label>
                                <p class="text-gray-900 font-bold">{{ $lembur->durasi_jam }} Jam</p>
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div>
                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Jam Mulai:</label>
                                <p class="text-gray-900">{{ $lembur->jam_mulai->format('H:i') }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Jam Selesai:</label>
                                <p class="text-gray-900">{{ $lembur->jam_selesai->format('H:i') }}</p>
                            </div>
                            
                            {{-- Status Approval --}}
                            <div class="mb-4">
                                <label class="form-label font-semibold text-gray-600 block">Status:</label>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ][$lembur->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="{{ $statusClass }} px-3 py-1 text-sm font-medium rounded-full">
                                    {{ ucfirst($lembur->status) }}
                                </span>
                            </div>

                            @if ($lembur->approver)
                                <div class="mb-4">
                                    <label class="form-label font-semibold text-gray-600 block">Disetujui/Ditolak Oleh:</label>
                                    {{-- Catatan: Perhatikan variabel $lembur->approved. Jika relasi di model Anda bernama 'approver', gunakan $lembur->approver->name --}}
                                    <p class="text-gray-900">{{ $lembur->approved->name ?? 'N/A' }} pada {{ $lembur->approved_at ?? 'N/A' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Bagian Uraian Pekerjaan dan Keterangan (Penuh Lebar) --}}
                    <div class="border-t pt-4 mt-4">
                        <div class="mb-6">
                            <label class="form-label font-semibold text-gray-600 block">Keterangan Tambahan (Alasan Lembur):</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-lg border italic">{{ $lembur->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
                        </div>
    
                        <div class="mb-6">
                            <label class="form-label font-semibold text-gray-600 block mb-2">Uraian Pekerjaan Lembur:</label>
                            <ul class="list-disc list-inside space-y-1 ml-4">
                                @forelse ($lembur->detailLemburs as $detail)
                                    <li class="text-gray-900">{{ $detail->uraian_pekerjaan }}</li>
                                @empty
                                    <li class="text-gray-500">Tidak ada uraian pekerjaan yang dicatat.</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- TAMBAHKAN RINCIAN PERKALIAN UANG LEMBUR -->
                         
                        <div class="mb-6">
                            <label class="form-label font-semibold text-gray-600 block mb-2">Rincian Perhitungan Uang Lembur:</label>
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                @php
                                    $upahSebulan = Auth::user()->gaji_pokok + Auth::user()->gaji_tunjangan_makan + Auth::user()->gaji_tunjangan_tetap  ?? 9000000;
                                    $upahPerJam = $upahSebulan / 173;
                                    $durasiJam = $lembur->durasi_jam;
                                    $estimasi_uang_lembur_calculated = 0;
                                @endphp

                                <p class="text-gray-900 mb-2"><strong>Upah Pokok + Tunjangan (per bulan):</strong> Rp {{ number_format($upahSebulan, 0, ',', '.') }}</p>
                                <p class="text-gray-900 mb-2"><strong>Upah Per Jam (1/173 x Upah Sebulan):</strong> Rp {{ number_format($upahPerJam, 0, ',', '.') }}</p>
                                <p class="text-gray-900 mb-2"><strong>Durasi Lembur Efektif:</strong> {{ $durasiJam }} Jam</p>

                                @if ($lembur->jenis == 'weekday')
                                    <p class="text-gray-900 font-medium mt-3">Perhitungan Lembur Hari Kerja:</p>
                                    @if ($durasiJam == 1)
                                        <p class="text-gray-900 ml-4">1 Jam Pertama: 1.5 x Upah Per Jam = 1.5 x Rp {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format(1.5 * $upahPerJam, 0, ',', '.') }}</p>
                                        @php $estimasi_uang_lembur_calculated = 1.5 * $upahPerJam; @endphp
                                    @elseif ($durasiJam > 1)
                                        <p class="text-gray-900 ml-4">1 Jam Pertama: 1.5 x Upah Per Jam = 1.5 x Rp {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format(1.5 * $upahPerJam, 0, ',', '.') }}</p>
                                        <p class="text-gray-900 ml-4">{{ $durasiJam - 1 }} Jam Berikutnya: {{ $durasiJam - 1 }} x 2 x Upah Per Jam = {{ $durasiJam - 1 }} x 2 x Rp {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format(($durasiJam - 1) * 2 * $upahPerJam, 0, ',', '.') }}</p>
                                        @php $estimasi_uang_lembur_calculated = (1.5 * $upahPerJam) + (($durasiJam - 1) * 2 * $upahPerJam); @endphp
                                    @endif
                                @elseif ($lembur->jenis == 'weekend')
                                    <p class="text-gray-900 font-medium mt-3">Perhitungan Lembur Akhir Pekan:</p>
                                    @if ($durasiJam >= 1 && $durasiJam <= 8)
                                        <p class="text-gray-900 ml-4">{{ $durasiJam }} Jam: {{ $durasiJam }} x 2 x Upah Per Jam = {{ $durasiJam }} x 2 x Rp {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format($durasiJam * 2 * $upahPerJam, 0, ',', '.') }}</p>
                                        @php $estimasi_uang_lembur_calculated = $durasiJam * 2 * $upahPerJam; @endphp
                                    @elseif ($durasiJam > 8)
                                        <p class="text-gray-900 ml-4">8 Jam Pertama: 8 x 2 x Upah Per Jam = 8 x 2 x Rp {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format(8 * 2 * $upahPerJam, 0, ',', '.') }}</p>
                                        <p class="text-gray-900 ml-4">{{ $durasiJam - 8 }} Jam Berikutnya: {{ $durasiJam - 8 }} x 3 x Upah Per Jam = {{ $durasiJam - 8 }} x 3 x {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format(($durasiJam - 8) * 3 * $upahPerJam, 0, ',', '.') }}</p>
                                        @php $estimasi_uang_lembur_calculated = (8 * 2 * $upahPerJam) + (($durasiJam - 8) * 3 * $upahPerJam); @endphp
                                    @endif
                                @elseif ($lembur->jenis == 'holiday')
                                    <p class="text-gray-900 font-medium mt-3">Perhitungan Lembur Hari Libur Nasional:</p>
                                    <p class="text-gray-900 ml-4">{{ $durasiJam }} Jam: {{ $durasiJam }} x 3 x Upah Per Jam = {{ $durasiJam }} x 3 x Rp {{ number_format($upahPerJam, 0, ',', '.') }} = Rp {{ number_format($durasiJam * 3 * $upahPerJam, 0, ',', '.') }}</p>
                                    @php $estimasi_uang_lembur_calculated = $durasiJam * 3 * $upahPerJam; @endphp
                                @endif

                                <p class="text-gray-900 mt-4 text-lg font-bold">Total Estimasi Uang Lembur: Rp {{ number_format($estimasi_uang_lembur_calculated, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="mt-8 pt-4 border-t space-x-3">
                        <a href="{{ route('lembur.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Kembali</a>
                        
                        @if (Auth::id() === $lembur->user_id && $lembur->status === 'pending')
                            <a href="{{ route('lembur.edit', $lembur->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Edit Pengajuan</a>
                        @endif
                        
                        @can('approve-lembur')
                            @if ($lembur->status === 'pending')
                                <form action="{{ route('lembur.approve', $lembur->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">✅ Setujui</button>
                                </form>
                                <form action="{{ route('lembur.reject', $lembur->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">❌ Tolak</button>
                                </form>
                            @endif
                            {{-- Tombol Cetak selalu tersedia untuk yang bisa approve, atau bisa juga disesuaikan dengan role lain --}}
                            <a href="{{ route('lembur.print', $lembur->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out" target="_blank">🖨️ Cetak</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>