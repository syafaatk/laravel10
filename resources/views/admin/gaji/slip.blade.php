<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Slip Gaji') }}
            </h2>
            <div class="flex space-x-2">
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.gaji.show', $slipGaji->gaji->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                @else
                    <a href="{{ route('slip-gaji.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali</a>
                @endif
                <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Cetak Slip
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="grid {{ Auth::user()->hasRole('admin') ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1' }} gap-8">
            <!-- Left Column: Slip Display -->
            <div class="max-w-2xl {{ !Auth::user()->hasRole('admin') ? 'mx-auto' : '' }}">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8" id="printable-area">
                    
                    {{-- Header Slip --}}
                    <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
                        <h1 class="text-2xl font-bold uppercase tracking-wider">PT. NAMA PERUSAHAAN ANDA</h1>
                        <p class="text-sm text-gray-600">Alamat Perusahaan Lengkap Di Sini</p>
                        <h2 class="text-xl font-bold mt-4 underline">SLIP GAJI KARYAWAN</h2>
                        <p class="text-gray-700">Periode: {{ $slipGaji->gaji->periode_bulan }}</p>
                    </div>

                    {{-- Employee Info --}}
                    <div class="grid grid-cols-1 gap-4 mb-6 text-sm">
                        <div>
                            <div class="grid grid-cols-3">
                                <span class="font-semibold">Nama</span>
                                <span class="col-span-2">: {{ $slipGaji->user->name }}</span>
                            </div>
                            <div class="grid grid-cols-3">
                                <span class="font-semibold">NIK / No. Peg</span>
                                <span class="col-span-2">: {{ $slipGaji->user->nopeg ?? '-' }}</span>
                            </div>
                            <div class="grid grid-cols-3">
                                <span class="font-semibold">Jabatan</span>
                                <span class="col-span-2">: {{ $slipGaji->user->jabatan ?? '-' }}</span>
                            </div>
                            <div class="grid grid-cols-3">
                                <span class="font-semibold">Status</span>
                                <span class="col-span-2">: Karyawan Tetap</span>
                            </div>
                            <div class="grid grid-cols-3">
                                <span class="font-semibold">Tanggal Cetak</span>
                                <span class="col-span-2">: {{ now()->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Content Table --}}
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        
                        {{-- PENDAPATAN --}}
                        <div>
                            <h3 class="font-bold text-lg border-b border-gray-400 mb-2">PENERIMAAN</h3>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="py-1">Gaji Pokok</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->gaji_pokok, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Tunjangan Jabatan</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_jabatan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Tunjangan Golongan</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_golongan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Tunjangan Makan</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_makan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Tunjangan Rumah</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_rumah, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Tunjangan Transport</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_transport, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Tunjangan Tambahan</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_tambahan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Uang Lembur</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->tunjangan_extra, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Premi JKK & JKM (Prsh)</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->premi_jkk_jkm, 0, ',', '.') }}</td>
                                </tr>
                                
                                <tr class="font-bold border-t border-gray-400">
                                    <td class="py-2">TOTAL PENGHASILAN BRUTO</td>
                                    <td class="text-right py-2">Rp {{ number_format($slipGaji->penghasilan_bruto, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>

                        {{-- POTONGAN --}}
                        <div>
                            <h3 class="font-bold text-lg border-b border-gray-400 mb-2">POTONGAN</h3>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="py-1">PPh 21</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->potongan_pph21, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">BPJS Ketenagakerjaan (JMO)</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->potongan_jmo, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Potongan Lain-lain</td>
                                    <td class="text-right">Rp {{ number_format($slipGaji->potongan_lain, 0, ',', '.') }}</td>
                                </tr>
                                
                                <tr class="font-bold border-t border-gray-400">
                                    <td class="py-2">TOTAL POTONGAN</td>
                                    <td class="text-right py-2">Rp {{ number_format($slipGaji->total_potongan, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- NETTO --}}
                    <div class="border-t-2 border-b-2 border-gray-800 py-4 mb-8 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold uppercase">Total Diterima (Take Home Pay)</h3>
                            <h3 class="text-2xl font-bold text-indigo-700">Rp {{ number_format($slipGaji->penghasilan_netto, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    {{-- Footer / Signatures --}}
                    <div class="grid grid-cols-2 gap-8 mt-12 text-center text-sm">
                        <div>
                            <p class="mb-16">Penerima,</p>
                            <p class="font-bold underline">{{ $slipGaji->user->name }}</p>
                        </div>
                        <div>
                            <p class="mb-16">Finance / HRD,</p>
                            <p class="font-bold underline">Admin Keuangan</p>
                        </div>
                    </div>

                    <div class="mt-8 text-xs text-center text-gray-400">
                        <p>Dokumen ini dicetak secara otomatis oleh sistem.</p>
                    </div>

                </div>
            </div>

            <!-- Right Column: Edit Form -->
            @if(Auth::user()->hasRole('admin'))
                <div class="max-w-2xl no-print">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <form action="{{ route('admin.slip-gaji.update', $slipGaji->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="gaji_id" value="{{ $slipGaji->gaji_id }}">
                            <input type="hidden" name="user_id" value="{{ $slipGaji->user_id }}">
                            
                            <h3 class="font-bold text-lg border-b border-gray-400 mb-4">PENERIMAAN</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Gaji Pokok</label>
                                    <input type="number" name="gaji_pokok" value="{{ $slipGaji->gaji_pokok }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Tunjangan Jabatan</label>
                                    <input type="number" name="tunjangan_jabatan" value="{{ $slipGaji->tunjangan_jabatan }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Tunjangan Golongan</label>
                                    <input type="number" name="tunjangan_golongan" value="{{ $slipGaji->tunjangan_golongan }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Tunjangan Makan</label>
                                    <input type="number" name="tunjangan_makan" value="{{ $slipGaji->tunjangan_makan }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Tunjangan Rumah</label>
                                    <input type="number" name="tunjangan_rumah" value="{{ $slipGaji->tunjangan_rumah }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Tunjangan Transport</label>
                                    <input type="number" name="tunjangan_transport" value="{{ $slipGaji->tunjangan_transport }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Tunjangan Tambahan</label>
                                    <input type="number" name="tunjangan_tambahan" value="{{ $slipGaji->tunjangan_tambahan }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Uang Lembur</label>
                                    <input type="number" name="tunjangan_extra" value="{{ $slipGaji->tunjangan_extra }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Premi JKK & JKM (Prsh)</label>
                                    <input type="number" name="premi_jkk_jkm" value="{{ $slipGaji->premi_jkk_jkm }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                            </div>

                            <h3 class="font-bold text-lg border-b border-gray-400 mb-4 mt-8">POTONGAN</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">PPh 21</label>
                                    <input type="number" name="potongan_pph21" value="{{ $slipGaji->potongan_pph21 }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">BPJS Ketenagakerjaan (JMO)</label>
                                    <input type="number" name="potongan_jmo" value="{{ $slipGaji->potongan_jmo }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-semibold mb-2 text-sm">Potongan Lain-lain</label>
                                    <input type="number" name="potongan_lain" value="{{ $slipGaji->potongan_lain }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                                </div>
                            </div>

                            <h3 class="font-bold text-lg border-b border-gray-400 mb-4 mt-8">CATATAN</h3>
                            <div class="mb-6">
                                <textarea name="catatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="3">{{ $slipGaji->catatan }}</textarea>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-area, #printable-area * {
                visibility: visible;
            }
            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
            /* Ensure background colors print */
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }
    </style>
</x-app-layout>