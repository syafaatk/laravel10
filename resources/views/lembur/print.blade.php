<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penugasan Lembur</title>
    <style>
        /* Gaya dasar untuk menyerupai dokumen resmi (Times New Roman, ukuran kecil) */
        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 0;
            font-size: 10pt; /* Lebih kecil agar muat dan mirip cetakan dokumen */
            /* landscape */
            size: A4;
            margin: 0;
        }
        @page {
        }
        .container {
            width: 29cm; /* Tinggi A4 */
            min-height: 19cm; /* Lebar A4 */
            padding: 0; /* Padding standar untuk dokumen */
            margin: 0 auto;
            /* Hapus border dan shadow agar terlihat seperti dokumen yang dicetak */
        }
        
        /* Bagian Keterangan Atasan/Yang Menugaskan */
        /* CSS Khusus untuk bagian ini */
        .header-info-table {
            width: 100%; /* Menggunakan lebar penuh kontainer */
            border-collapse: collapse;
            line-height: 1.4; /* Memberi sedikit ruang antar baris */
            font-size: 10pt; /* Sesuaikan ukuran font agar mirip dokumen */
        }
        .header-info-table td {
            padding: 0;
            vertical-align: top;
            white-space: nowrap; /* Mencegah label kolom terpotong */
        }
        /* Kolom 1 (Label Nama/Jabatan) */
        .header-info-table td:nth-child(1) {
            width: 100px; /* Lebar tetap untuk label 'N a m a' dan 'Jabatan' */
            padding-right: 5px; /* Sedikit jarak setelah label */
        }
        /* Kolom 2 (Nilai Nama/Jabatan) */
        .header-info-table td:nth-child(2) {
            width: 50%; /* Mengambil sisa lebar yang diperlukan */
        }
        /* Kolom 3 (Label No. PTBA/Kode Satker) */
        .header-info-table td:nth-child(3) {
            width: 90px; /* Lebar tetap untuk label 'No. PTBA' dan 'Kode Satker' */
            padding-left: 15px; /* Jarak dari kolom nilai sebelumnya */
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
            background-color: #f0f0f0; /* Opsional: sedikit abu-abu untuk header */
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
            /* checked */
            background-color: black;
            border: 1px solid black;
            /* Menggunakan pseudo-element untuk tanda centang */
        }
        .checkbox-container input[type="checkbox"]:checked::before {
            content: '✔'; /* Unicode checkmark character */
            display: block;
            color: white;
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
            height: auto; /* Biarkan tinggi menyesuaikan konten */
        }
        .employee-details-table th {
            text-align: center;
            background-color: white; /* Hapus warna latar belakang header */
        }
        .employee-details-table td.uraian-pekerjaan {
            padding: 0;
        }
        .employee-details-table td.uraian-pekerjaan div {
            border-bottom: 1px solid black;
            padding: 4px;
            height: 1.5em; /* Ketinggian baris untuk setiap uraian */
        }
        .employee-details-table td.uraian-pekerjaan div:last-child {
            border-bottom: none;
        }
        
        /* Kolom lebar spesifik untuk tabel pegawai */
        .employee-details-table col.no { width: 3%; }
        .employee-details-table col.nik { width: 10%; }
        .employee-details-table col.nama { width: 20%; }
        .employee-details-table col.pp { width: 15%; }
        .employee-details-table col.uraian { width: 52%; }

        /* Tanda Tangan */
        .signature-block {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature-block > div {
            width: 45%;
            text-align: left; /* Teks Tanda tangan rata kiri */
            line-height: 1.5;
        }
        .signature-block .signer {
            margin-top: 60px; /* Jarak untuk tanda tangan */
            font-weight: bold;
        }
        .signature-block .details {
            margin-top: 5px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header-info">
            <table class="header-info-table">
                <tr>
                    <td>Yang bertanda tangan dibawah ini:</td>
                    <td></td>
                </tr>
                <!-- perbaiki table padding dan marginnya -->
                <tr>
                    <td>N a m a</td>
                    <td>: Dedek Apriyani</td>
                    <td>No. PTBA</td>
                    <td>: 30864</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: Application Development & Services / EIS Department Head</td>
                    <td>Kode Divisi</td>
                    <td>: 13410</td>
                </tr>
            </table>
        </div>

        <div class="assignment-details">
            Dengan ini menugaskan pegawai tersebut di bawah ini untuk melaksanakan tugas kerja lembur dengan rincian :

            <table class="jenis-lembur-table">
                <tr>
                    <td rowspan="2" style="width: 15%; border: 1px solid black; text-align: center; vertical-align: middle">Jenis Lembur:</td>
                    <td rowspan="2" style="width: 20%; border: 1px solid black; text-align: left; vertical-align: middle; padding: 10px;">
                        <div class="checkbox-container">
                            <input type="checkbox" id="weekday" {{ $lembur->jenis == 'weekday' ? 'checked' : '' }}>
                            <label for="weekday">Lembur Hari Kerja</label>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" id="weekend" {{ $lembur->jenis == 'weekend' ? 'checked' : '' }}>
                            <label for="weekend">Lembur Akhir Pekan</label>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" id="holiday" {{ $lembur->jenis == 'holiday' ? 'checked' : '' }}>
                            <label for="holiday">Lembur Hari Libur Resmi</label>
                        </div>
                        
                    </td>
                    <td rowspan="2" style="width: 25%; border: 1px solid black; text-align: center; vertical-align: middle">Tanggal :{{ $lembur->tanggal->format('d-M-Y') }}<br>Lembur : Pekerjaan</td>
                    <td style="width: auto; border: 1px solid black; text-align: center; vertical-align: middle;">Mulai</td>
                    <td style="width: auto; border: 1px solid black; text-align: center; vertical-align: middle;">Selesai</td>
                    <td style="width: auto; border: 1px solid black; text-align: center; vertical-align: middle;">Jumlah Jam</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; text-align: center;">{{ $lembur->jam_mulai->format('H.i') }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $lembur->jam_selesai->format('H.i') }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $lembur->durasi_jam }} Jam</td>
                </tr>
            </table>
        </div>

        <div class="content">
            Alasan lembur :
            <table class="employee-details-table">
                <colgroup>
                    <col class="no">
                    <col class="nik">
                    <col class="nama">
                    <col class="pp">
                    <col class="uraian">
                </colgroup>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>PP</th>
                        <th>Uraian Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: center;">{{ $lembur->user->nopeg }}</td>
                        <td>{{ $lembur->user->name }}</td>
                        <td>Setara MJL 13</td>
                        <td class="uraian-pekerjaan">
                            @foreach ($lembur->detailLemburs as $detail)
                                <div>{{ $detail->uraian_pekerjaan }}</div>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <p>Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>

        <div class="signature-block">
            <div style="text-align: center;">
                Menyetujui<br>
                Application Development & Services / EIS Department Head
                
                <div class="signer">DEDEK APRIYANI</div>
                <div class="details">8913230864</div>
            </div>
            <div style="text-align: center;">
                Yang Menugaskan<br>
                PIC (Penanggung Jawab)
                
                <div class="signer">
                    @if ($lembur->approver)
                            @php
                                $approvers = [
                                    '9520131577' => 'FITHRI HALIM AHMAD',
                                    '8913230864' => 'DEDEK APRIYANI',
                                    '8916131158' => 'ARYA REZA NUGRAHA',
                                    '8520131736' => 'ASEP MARYANA',
                                    '9824132111' => 'ZULFIKAR MURAKABIMAN',
                                ];
                            @endphp
                            {{ $approvers[$lembur->approver] ?? $lembur->approver }}
                    @else
                        -
                    @endif
                </div>
                <div class="details">{{ $lembur->approver }}</div>
            </div>
        </div>
    </div>
</body>
</html>