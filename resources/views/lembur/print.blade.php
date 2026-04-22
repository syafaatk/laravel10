<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Surat Penugasan Lembur') }}
        </h2>
    </x-slot>

    {{-- STYLE KHUSUS UNTUK DOKUMEN & PRINT --}}
    <style>
        body {
            font-family: 'Verdana', sans-serif;
            font-color: #000000;
            font-size: 11pt;
        }

        @page {
            size: A4 landscape;
            margin-left: 2cm;
            margin-right: 1cm;
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
        }
        
        /* Bagian Keterangan Atasan/Yang Menugaskan */
        /* CSS Khusus untuk bagian ini */
        .header-info-table {
            width: 100%; /* Menggunakan lebar penuh kontainer */
            border-collapse: collapse;
            line-height: 1;
            font-size: 11pt; /* Sesuaikan ukuran font agar mirip dokumen */
        }
        .header-info-table td {
            padding: 0;
            vertical-align: top;
            white-space: nowrap; /* Mencegah label kolom terpotong */
        }
        /* Kolom 1 (Label Nama/Jabatan) */
        .header-info-table td:nth-child(1) {
            width: 100px;
            padding-right: 5px;
        }
        /* Kolom 2 (Nilai Nama/Jabatan) */
        .header-info-table td:nth-child(2) {
            width: 50%;
        }
        /* Kolom 3 (Label No. PTBA/Kode Satker) */
        .header-info-table td:nth-child(3) {
            width: 90px;
            padding-left: 15px;
            padding-right: 5px;
        }
        /* Kolom 4 (Nilai No. PTBA/Kode Satker) */
        .header-info-table td:nth-child(4) {
            width: 15%;
        }
        /* Bagian Penugasan dan Rincian Lembur */
        .assignment-details {
            margin-bottom: 20px;
        }
        .assignment-details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .assignment-details-table th, .assignment-details-table td {
            border: 1px solid black;
            padding: 4px 8px;
            text-align: left;
            vertical-align: top;
        }
        .assignment-details-table th {
            background-color: #f0f0f0;
        }
        
        /* Table Jenis Lembur */
        .jenis-lembur-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .jenis-lembur-table td {
            padding: 2px 0;
            vertical-align: middle;
        }
        .jenis-lembur-table td:nth-child(2) {
            width: 10%;
        }
        .jenis-lembur-table td:nth-child(3) {
            width: 15%;
            text-align: center;
        }
        .jenis-lembur-table td:nth-child(4) {
            width: 15%;
            text-align: center;
        }
        .jenis-lembur-table td:nth-child(5) {
            width: 10%;
            text-align: center;
        }
        
        /* Checkbox Styling */
        .checkbox-container {
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
        }
        .checkbox-container input[type="checkbox"] {
            margin-right: 5px;
            height: 10px;
            width: 10px;
            /* Styling untuk membuat checkbox terlihat seperti yang dicentang di gambar */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: 1px solid black;
            padding: 1px;
            outline: none;
            position: relative;
        }
        .checkbox-container input[type="checkbox"]:checked {
            background-color: black;
            border: 1px solid black;
            /* Menggunakan pseudo-element untuk tanda centang */
        }
        .checkbox-container input[type="checkbox"]:checked::before {
            display: block;
            color: black;
            font-size: 8pt;
            line-height: 1;
            text-align: center;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Bagian Alasan Lembur / Rincian Pegawai */
        .employee-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .employee-details-table th, .employee-details-table td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            vertical-align: top;
            height: auto;
        }
        .employee-details-table th {
            text-align: center;
            background-color: white;
        }
        .employee-details-table td.uraian-pekerjaan {
            padding: 0;
        }
        .employee-details-table td.uraian-pekerjaan div {
            border-bottom: 1px solid black;
            padding: 4px;
            min-height: 1.5em; /* Gunakan min-height agar bisa lebih tinggi jika konten panjang */
        }
        .employee-details-table td.uraian-pekerjaan div:last-child {
            border-bottom: none;
        }
        
        /* Kolom lebar spesifik untuk tabel pegawai */
        .employee-details-table col.no { width: 3%; }
        .employee-details-table col.karyawan { width: 10%; }
        .employee-details-table col.nama { width: 10%; }
        .employee-details-table col.shift { width: 5%; }
        .employee-details-table col.uraian { width: 50%; }
        .employee-details-table col.mulai { width: 8%; }
        .employee-details-table col.selesai { width: 8%; }
        .employee-details-table col.jam { width: 6%; }

        /* Tanda Tangan */
        .signature-block {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature-block > div {
            width: 45%;
            text-align: left;
            line-height: 1;
        }
        .signature-block .signer {
            margin-top: 80px;
            font-size: 11pt;
            font-weight: bold;
        }
        .signature-block .details {
            margin-top: 5px;
            font-size: 11pt;
        }

        /* PRINT: hanya area kanan (#print-area) yang dicetak */
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="py-8" style="background-color: #ffffff;">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- GRID 2 KOLOM --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KIRI: DETAIL PENGAJUAN LEMBUR --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 no-print">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Detail Pengajuan Lembur #{{ $lembur->id }}
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div><span class="font-semibold text-gray-700">Nama Karyawan:</span> <span class="text-gray-800">{{ $lembur->user->name }}</span></div>
                        <div><span class="font-semibold text-gray-700">Tanggal Lembur:</span> <span class="text-gray-800">{{ $lembur->tanggal->format('d F Y') }}</span></div>
                        <div><span class="font-semibold text-gray-700">Jenis Lembur:</span> <span class="text-gray-800">{{ ucfirst($lembur->jenis) }}</span></div>
                        <div><span class="font-semibold text-gray-700">Jam:</span> <span class="text-gray-800">{{ $lembur->jam_mulai->format('H:i') }} - {{ $lembur->jam_selesai->format('H:i') }}</span></div>
                        <div><span class="font-semibold text-gray-700">Durasi:</span> <span class="text-gray-800">{{ $lembur->durasi_jam }} Jam</span></div>
                        <div><span class="font-semibold text-gray-700">Status:</span>
                            <span class="inline-flex items-center px-2 py-1 text-[11px] rounded-full
                                @if($lembur->status == 'pending') bg-amber-100 text-amber-700
                                @elseif($lembur->status == 'approved') bg-emerald-100 text-emerald-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($lembur->status) }}
                            </span>
                        </div>
                        <div class="pt-2 border-t border-dashed">
                            <span class="font-semibold text-gray-700 block mb-1">Uraian Pekerjaan:</span>
                            <ul class="list-disc list-inside space-y-1 ml-1 text-gray-800">
                                @foreach ($lembur->detailLemburs as $detail)
                                    <li>{{ $detail->uraian_pekerjaan }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-2">
                        <a href="{{ route('lembur.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100">
                            Kembali ke Daftar
                        </a>
                    </div>
                    <div class="mt-4 text-xs text-gray-500">
                        Catatan: Hanya surat penugasan lembur yang akan dicetak. Informasi di sebelah kiri ini tidak akan muncul di hasil cetakan.
                    </div>
                    <!-- Estimasi uang lembur -->
                    <div class="mt-4 text-2xl text-gray-500">
                        Estimasi Uang Lembur: <span class="font-semibold text-gray-700">Rp {{ number_format($lembur->estimasi_uang_lembur, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- KANAN: SURAT PENUGASAN LEMBUR + TOMBOL PRINT --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-4 col-span-2">
                    <div class="flex items-center justify-between mb-3 no-print">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                Surat Penugasan Lembur (Preview)
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Klik tombol <strong>Print</strong> untuk mencetak hanya formulir ini.
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('lembur.download', $lembur) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-red-600 text-white text-xs font-semibold hover:bg-red-700">Download PDF</a>
                            <button type="button" onclick="printDocument()" class="inline-flex items-center px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a2 2 0 00-2 2v3h12V4a2 2 0 00-2-2H6z" /><path d="M4 9a2 2 0 00-2 2v3h3v3h10v-3h3v-3a2 2 0 00-2-2H4z" /></svg>
                                Print Form
                            </button>
                        </div>
                    </div>

                    {{-- AREA YANG AKAN DI-PRINT --}}
                    <div id="print-area" style="background-color: white; padding: 20px;">
                        <!-- judul surat penugasan lembur-->
                        <div style="text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 40px;">
                            SURAT PERINTAH KERJA LEMBUR (SPKL)
                        </div>

                        <div class="header-info">
                            <table class="header-info-table">
                                <colgroup>
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                    <col style="width: 60%;">
                                    <col style="width: 10%;">
                                    <col style="width: 10%;">
                                </colgroup>
                                <tr>
                                    <td colspan="2">Yang bertanda tangan dibawah ini:</td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>: Dedek Apriyani</td>
                                    <td></td>
                                    <td>No. PTBA</td>
                                    <td>: 8913230864</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>: Application Development & Services / EIS Department Head</td>
                                    <td></td>
                                    <td>Kode Divisi</td>
                                    <td>: 134100000N</td>
                                </tr>
                            </table>
                        </div>

                        <div class="assignment-details" style="margin-top: 20px;">
                            Dengan ini menugaskan kepada pegawai tersebut di bawah ini untuk melaksanakan tugas kerja lembur dengan rincian :
                            <table class="jenis-lembur-table" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td class="tg-0pky" style="line-height: 1;width: 10%; font-size:11pt; border: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold;" rowspan="3">JENIS LEMBUR</td>
                                    <td class="tg-0pky" style="line-height: 1;width: 1%; border: 1px solid black; text-align: center; vertical-align: middle; {{ $lembur->jenis == 'weekday' ? 'background-color: black;' : '' }}"></td>
                                    <td class="tg-0pky" style="line-height: 1;width: 25%; font-size:11pt; border: 1px solid black; border-bottom: none; text-align: left; vertical-align: middle; padding: 2px 5px;">Lembur hari Kerja / Nerus</td>
                                    <td class="tg-0pky" style="line-height: 1;width: 16%; font-size:11pt; border: 1px solid black; text-align: center; vertical-align: middle; font-weight: bold;">JADWAL LEMBUR</td>
                                </tr>
                                <tr>
                                    <td class="tg-0pky" style="line-height: 1;width: 1%; border: 1px solid black; border-top: none; text-align: center; vertical-align: middle; {{ $lembur->jenis == 'weekend' ? 'background-color: black;' : '' }}"></td>
                                    <td class="tg-0pky" style="line-height: 1;width: 25%; font-size:11pt; border: 1px solid black; border-top: none; border-bottom: none; text-align: left; vertical-align: middle; padding: 2px 5px;">Lembur Akhir Pekan</td>
                                    <td class="tg-0pky" style="line-height: 1;width: 16%; font-size:11pt; border: 1px solid black; text-align: left; vertical-align: middle; padding-left:5px;" rowspan="2">
                                        <table style="width: 100%; border-collapse: collapse; border: none;">
                                            <tr>
                                                <td style="border: none; padding-left:2px; width: 2%;">Hari</td>
                                                <td style="border: none; width: 1%;">:</td>
                                                <td style="border: none; padding-left:2px; text-align: left;">{{ $lembur->tanggal->translatedFormat('l') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding-left:2px;">Tanggal</td>
                                                <td style="border: none; width: 1%;">:</td>
                                                <td style="border: none; padding-left:2px; text-align: left;">{{ $lembur->tanggal->translatedFormat('d F Y') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="tg-0pky" style="line-height: 1;width: 1%; border: 1px solid black; text-align: center; vertical-align: middle; {{ $lembur->jenis == 'holiday' ? 'background-color: black;' : '' }}"></td>
                                    <td class="tg-0pky" style="line-height: 1;width: 25%; font-size:11pt; border: 1px solid black; border-top: none; text-align: left; vertical-align: middle; padding: 2px 5px;">Lembur Nasional / Resmi</td>
                                </tr> 
                            </table>
                        </div>

                        <div class="content">
                            Alasan lembur :
                            <table class="employee-details-table" style="line-height: 1; width: 100%; border-collapse: collapse; margin-top: 5px;">
                                <colgroup>                                    
                                    <col class="no">
                                    <col class="karyawan">
                                    <col class="nama">
                                    <col class="shift">
                                    <col class="uraian">
                                    <col class="mulai">
                                    <col class="selesai">
                                    <col class="jam">
                                </colgroup>
                                <thead>
                                    <tr><th>No</th><th>Karyawan</th><th>Nama</th><th>Shift</th><th>Uraian Pekerjaan</th><th>Mulai</th><th>Selesai</th><th>Jam</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle; width: 3%;">1</td>
                                        <td style="text-align: center; vertical-align: middle; width: 5%;">PT. Qtasnim Digital Teknologi</td>
                                        <td style="text-align: center; vertical-align: middle; width: 2%;">{{ $lembur->user->name }}</td>
                                        <td style="text-align: center; vertical-align: middle; width: 5%;">Non Shift</td>
                                        <td class="uraian-pekerjaan" style="width: 30%;">
                                            @foreach ($lembur->detailLemburs as $detail)
                                                <div style="border-bottom: none;">{{ $detail->uraian_pekerjaan }}</div>
                                            @endforeach
                                            {{-- Tambahkan baris kosong jika uraian kurang dari 3 untuk menjaga tinggi tabel --}}
                                            @for ($i = count($lembur->detailLemburs); $i < 3; $i++)
                                                <div>&nbsp;</div>
                                            @endfor
                                        </td>
                                        <td style="text-align: center; padding: 2px 5px;">{{ $lembur->jam_mulai->format('H:i') }}</td>
                                        <td style="text-align: center; padding: 2px 5px;">{{ $lembur->jam_selesai->format('H:i') }}</td>
                                        <td style="text-align: center; padding: 2px 5px;">{{ $lembur->durasi_jam }} Jam</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <p style="margin-top: 20px;">Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>

                        <div class="signature-block">
                            <div style="text-align: center;">
                                Yang Menugaskan<br>
                                @if ($lembur->approver)
                                        @php
                                            $approvers = [
                                                '9520131577' => 'Senior Programmer Specialist',
                                                '8913230864' => 'Application Development & Services / EIS Department Head',
                                                '8916131158' => 'Senior Production Information System Specialist',
                                                '8520131736' => 'Senior Programmer Specialist',
                                                '9824132111' => 'Programming Staff',
                                            ];
                                        @endphp
                                        {{ $approvers[$lembur->approver] ?? $lembur->approver }}
                                    @else
                                        -
                                    @endif
                                <br>
                                <br>
                                <div class="signer" style="font-weight: bold; margin-top: 80px;">
                                    <!-- tambahkan undeline pada nama -->
                                     
                                    <span style="text-decoration: underline; text-weight: bold;">
                                    @if ($lembur->approver)
                                        @php
                                            $approvers = [
                                                '9520131577' => 'Fithri Halim Ahmad',
                                                '8913230864' => 'Dedek Apriyani',
                                                '8916131158' => 'Arya Reza Nugraha',
                                                '8520131736' => 'Asep Maryana',
                                                '9824132111' => 'Zulfikar Murakabiman',
                                            ];
                                        @endphp
                                        {{ $approvers[$lembur->approver] ?? $lembur->approver }}
                                    @else
                                        -
                                    @endif
                                    </span>
                                </div>
                                <div class="details" style="text-align: center;font-weight: bold;">NIP.{{ $lembur->approver }}</div>
                            </div>
                            <div style="text-align: center;">
                                Menyetujui<br>
                                Application Development & Services / EIS <br>Department Head
                                <div class="signer"><span style="text-decoration: underline; text-weight: bold;">Dedek Apriyani</span></div>
                                <div class="details" style="font-weight: bold;font-size: 11pt;">NIP.8913230864</div>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function printDocument() {
            window.print();
        }
    </script>
</x-app-layout>