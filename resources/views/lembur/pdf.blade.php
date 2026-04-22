<style>
    body {
        font-family: 'Verdana', sans-serif;
        font-size: 11pt;
    }

    @page {
        size: A4 landscape;
        margin: 1cm;
    }
    /* Kolom lebar spesifik untuk tabel pegawai */
        .employee-details-table col.no { width: 3%; }
        .employee-details-table col.karyawan { width: 10%; }
        .employee-details-table col.nama { width: 10%; }
        .employee-details-table col.shift { width: 5%; }
        .employee-details-table col.uraian { width: 45%; }
        .employee-details-table col.mulai { width: 3%; }
        .employee-details-table col.selesai { width: 3%; }
        .employee-details-table col.jam { width: 3%; }
    
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

    .assignment-details {
        margin-top: 15px;
        margin-bottom: 20px;
    }

    /* Perbaikan Tabel Jenis Lembur */
    .jenis-lembur-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .jenis-lembur-table td {
        border: 1px solid black;
        padding: 5px;
        vertical-align: middle;
    }

    /* Perbaikan Checkbox agar tidak bertumpuk */
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 5px; /* Memberi jarak antar item */
    }
    .checkbox-container {
        display: flex;
        align-items: center;
        font-size: 11pt;
        line-height: 1;
    }
    .checkbox-container input[type="checkbox"] {
        margin-right: 8px;
        height: 12px;
        width: 12px;
        appearance: none;
        -webkit-appearance: none;
        border: 1px solid black;
        position: relative;
        cursor: default;
    }
    .checkbox-container input[type="checkbox"]:checked {
        background-color: #000;
    }
    .checkbox-container input[type="checkbox"]:checked::before {
        font-size: 11pt;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .employee-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }
    .employee-details-table th, .employee-details-table td {
        border: 1px solid black;
        padding: 4px;
    }
    .employee-details-table td.uraian-pekerjaan div {
        border-bottom: 1px solid black;
        padding: 2px 4px;
        min-height: 1.5em;
    }
    .employee-details-table td.uraian-pekerjaan div:last-child {
        border-bottom: none;
    }

    /* Perbaikan Tanda Tangan Kiri-Kanan */
    /* CSS Tanda Tangan Table-Based */
    .signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 40px;
        border: none;
    }
    .signature-table td {
        border: none !important; /* Memastikan tidak ada border */
        width: 50%;
        text-align: center;
        vertical-align: top;
        line-height: 1;
    }
    .signer-name {
        margin-top: 70px; /* Jarak untuk tanda tangan */
        font-weight: bold;
        text-decoration: underline;
        text-transform: uppercase;
    }
    /* Style untuk daftar jenis lembur */
    .jenis-lembur-list {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }
    .jenis-lembur-item {
        display: flex;
        align-items: center;
        padding-left: 5px;
        line-height: 1;
        margin-bottom: 0;
        font-size: 11pt;
    }
    .symbol {
        font-size: 11pt; /* Ukuran simbol sedikit lebih besar agar jelas */
        margin-right: 8px;
        font-family: "DejaVu Sans", "Arial Unicode MS", serif; /* Support simbol unicode */
    }

    @media print {
        #print-area { width: 100%; }
        .no-print { display: none; }
    }
</style>

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
                            <td style="border: none; padding: 2px 0; width: 2%;">Hari</td>
                            <td style="border: none; width: 1%;">:</td>
                            <td style="border: none; padding: 2px 0; text-align: left;">{{ $lembur->tanggal->translatedFormat('l') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 2px 0;">Tanggal</td>
                            <td style="border: none; width: 1%;">:</td>
                            <td style="border: none; padding: 2px 0; text-align: left;">{{ $lembur->tanggal->translatedFormat('d F Y') }}</td>
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
                    <td style="text-align: center; width: 10%; padding: 2px 5px;">{{ $lembur->jam_mulai->format('H:i') }}</td>
                    <td style="text-align: center; width: 10%; padding: 2px 5px;">{{ $lembur->jam_selesai->format('H:i') }}</td>
                    <td style="text-align: center; width: 10%; padding: 2px 5px;">{{ $lembur->durasi_jam }} Jam</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <p style="margin-top: 20px;">Demikian untuk dilaksanakan dengan penuh tanggung jawab.</p>

    <table class="signature-table">
        <tr>
            <td>
                Yang Menugaskan,<br>
                PIC (Penanggung Jawab)
                <div class="signer-name" style="margin-top: 80px;">
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
                <div>{{ $lembur->approver ?? '-' }}</div>
            </td>
            <td>
                Menyetujui,<br>
                Application Development & Services / <br>EIS Department Head
                <div class="signer-name">DEDEK APRIYANI</div>
                <div>8913230864</div>
            </td>
            
        </tr>
    </table>
</div>