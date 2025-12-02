<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengunduran Diri</title>
<style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt; /* Adjust font size to be more like a document */
            padding: 20px;
        }
        .container {
            max-width: 800px; /* Standard A4-ish width */
            margin: auto;
            border: 1px solid #000; /* Main border to resemble the document */
            padding-left: 45px;
            padding-right: 45px;
            padding-top: 10px;
            padding-bottom: 20px;
        }
        .header-section {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000; /* Separator line for header */
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo-box {
            width: 15%;
            padding-right: 15px;
        }
        .company-info {
            width: 85%;
            text-align: left;
        }
        .company-info p {
            margin-bottom: 0;
            line-height: 1.2;
        }
        .form-title {
            text-align: center;
            font-weight: bold;
            padding: 5px;
        }
        .applicant-info, .pengunduran-details, .bank-info {
            margin-bottom: 15px;
        }
        .applicant-info table, .pengunduran-details table, .bank-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .applicant-info td:first-child, .pengunduran-details td:first-child, .bank-info td:first-child {
            width: 30%;
            padding-right: 5px;
            vertical-align: top;
        }
        .applicant-info td, .pengunduran-details td, .bank-info td {
            padding: 1px 0;
        }

        /* pengunduran Details Table */
        .pengunduran-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .pengunduran-table th, .pengunduran-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .pengunduran-table th {
            text-align: center;
            background-color: #f2f2f2;
        }
        .pengunduran-table .project-row td {
            border-top: none;
            border-bottom: none;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 0;
        }
        .pengunduran-table .sub-total-row td {
            font-weight: bold;
            padding-top: 10px;
        }

        /* Footer sections */
        .note {
            margin-top: 20px;
            font-style: italic;
        }
        .bank-info {
            border-top: 1px solid #000;
            padding-top: 10px;
        }
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
        .signature-section {
            margin-top: 40px;
        }
        .signature-box {
            width: 50%;
            display: inline-block;
            text-align: left;
        }
        .signature-box .name-underline {
            display: block;
            margin-top: 85px; /* Space for signature */
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">

        <div style="padding: 5px; margin-bottom: 5px;">
            <div class="header-section">
                <div class="logo-box" style="border-right: 2px solid #000; padding-right: 10px; margin-right: 10px;">
                     <img src="http://qtasnim.com/wp-content/uploads/2025/03/cropped-Qtasnim-Digital-Teknologi-Logo.png" alt="Company Logo" style="max-width: 100%;">
                </div>
                <div class="company-info">
                    <p style="font-size: 14pt; font-weight: bold;">PT QTASNIM DIGITAL TEKNOLOGI</p>
                    <p>Padasuka Ideal Residence Blok A5 No. 11 Bandung Tlp. (022) 20529499, E-mail : office@qtasnim.com</p>
                    
                </div>
                
            </div>
            
        </div>
        <p class="form-title" style="margin-top: 0px;">FORMULIR PERMOHONAN PENGUNDURAN DIRI KARYAWAN</p>
        <div class="form-number" style="text-align: center;">
            <p>Nomor : {{ $report_data['form_number'] }} /Srt-PengunduranDiri/QT/{{ $report_data['form_number_month'] }}/{{ \Carbon\Carbon::now()->format('Y') }}</p>
        </div>
        
        <!-- surat pengunduran diri -->
         <!-- kepada yth -->
          <!-- lokasi dan tanggal -->
        <div class="date-location" style="text-align: right; margin-bottom: 15px; margin-right: 20px;">
            <p>Tanjung Enim, {{ $pengunduran->created_at->format('d F Y') }}</p>
        </div>
        <div class="applicant-info">
            <p>Kepada Yth,</p>
            <p>Bapak/Ibu Pimpinan</p>
            <p>HRD PT QTASNIM DIGITAL TEKNOLOGI</p>
            <p>Di Tempat</p>
            <br>
        </div>
        
        <div class="applicant-info">
            <p>Yang bertanda tangan di bawah ini:</p>
            <table>
                <tbody>
                    <tr>
                        <td>Nama</td>
                        <!-- user login -->
                        <td>: {{ $pengunduran->user->name }}</td>
                    </tr>
                    <tr>
                        <td>No Pegawai</td>
                        <td>: {{ $pengunduran->user->nopeg }}</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td>
                        <td>: TANJUNG ENIM</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="note">
            <p>Melalui surat ini, Saya mengajukan permohonan pengunduran diri dari PT. Qtasnim Digital Teknologi dari posisi {{ $pengunduran->user->jabatan }} efektif mulai tanggal {{ $pengunduran->requested_date->format('d F Y') }}.</p>
        </div>
        <div class="note">
            <p>{{ $pengunduran->reason }}</p>
        </div>
        <div class="note">
            <p>Akhir kata, saya berharap hubungan saya dengan rekan rekan di PT. Qtasnim Digital Teknologi dan PT. Bukit Asam Tbk. dapat terus berjalan dengan baik dan saya selalu mendoakan kesukseskan PT. Qtasnim Digital Teknologi. dan PT. Bukit Asam Tbk. di masa depan.</p>
        </div>

        <div class="signature-section">
            <!-- <div class="signature-box" style="float: left; width: 50%;"> -->
                
            <!-- </div> -->
            <div class="signature-box" style="float: left; width: 50%;">
                <p>Hormat Saya,</p>
                <p>Yang Mengajukan,</p>
                @if ($pengunduran->user->attachment_ttd)
                    <img src="{{ asset('storage/' . $pengunduran->user->attachment_ttd) }}" alt="Signature" style="max-width: 200px; max-height: 100px;">
                    <p style="margin-top: -10px;">{{ $pengunduran->user->name }}</p> 
                @else
                    <span class="name-underline">{{ $pengunduran->user->name }}</span>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>
        
        

        <div class="signature-block">
            <div style="text-align: center;">
                Diketahui<br>
                PIC (Penanggung Jawab)
                
                <div class="signer">
                    @if ($pengunduran->pic)
                            @php
                                $pic = [
                                    '9520131577' => 'FITHRI HALIM AHMAD',
                                    '8913230864' => 'DEDEK APRIYANI',
                                    '8916131158' => 'ARYA REZA NUGRAHA',
                                    '8520131736' => 'ASEP MARYANA',
                                    '9824132111' => 'ZULFIKAR MURAKABIMAN',
                                ];
                            @endphp
                            {{ $pic[$pengunduran->pic] ?? $pengunduran->pic }}
                    @else
                        -
                    @endif
                </div>
                <div class="details">{{ $pengunduran->pic }}</div>
            </div>
            <div style="text-align: center;">
                Menyetujui<br>
                APP. DEV. & SERVICES / EIS DEPT. HEAD
                
                <div class="signer">DEDEK APRIYANI</div>
                <div class="details">8913230864</div>
            </div>
            
        </div>
</body>
</html>