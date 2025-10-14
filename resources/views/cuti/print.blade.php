<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengajuan Cuti</title>
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
            padding: 15px;
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
        .applicant-info, .cuti-details, .bank-info {
            margin-bottom: 15px;
        }
        .applicant-info table, .cuti-details table, .bank-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .applicant-info td:first-child, .cuti-details td:first-child, .bank-info td:first-child {
            width: 30%;
            padding-right: 5px;
            vertical-align: top;
        }
        .applicant-info td, .cuti-details td, .bank-info td {
            padding: 1px 0;
        }

        /* cuti Details Table */
        .cuti-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .cuti-table th, .cuti-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .cuti-table th {
            text-align: center;
            background-color: #f2f2f2;
        }
        .cuti-table .project-row td {
            border-top: none;
            border-bottom: none;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 0;
        }
        .cuti-table .sub-total-row td {
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
        .signature-section {
            margin-top: 40px;
        }
        .signature-box {
            width: 50%;
            display: inline-block;
            text-align: center;
        }
        .signature-box .name-underline {
            display: block;
            margin-top: 85px; /* Space for signature */
            text-decoration: underline;
        }

        p {
            margin: 8px 0;
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
        <p class="form-title" style="margin-top: 0px;">FORMULIR PERMOHONAN CUTI KARYAWAN</p>
        <div class="form-number" style="text-align: center;">
            <p>Nomor : .... /Srt-Cuti/QT/{{ $report_data['form_number_month'] }}/{{ \Carbon\Carbon::now()->format('Y') }}</p>
        </div>
        
        <!-- surat cuti -->
         <!-- kepada yth -->
          <!-- lokasi dan tanggal -->
        <div class="date-location" style="text-align: right; margin-bottom: 15px; margin-right: 20px;">
            <p>Tanjung Enim, {{ $cuti->created_at->format('d F Y') }}</p>
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
                        <td>: {{ $cuti->user->name }}</td>
                    </tr>
                    <tr>
                        <td>No Pegawai</td>
                        <td>: {{ $cuti->user->nopeg }}</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td>
                        <td>: TANJUNG ENIM</td>
                    </tr>
                </tbody>
            </table>
            @if ($cuti->days_requested == 1)
                <p>Mengajukan Permohonan {{ $cuti->masterCuti->name }} selama {{ $cuti->days_requested }} hari kerja pada tanggal {{ $cuti->start_date->format('d F Y') }}</p>
            @else
                <p>Mengajukan Permohonan {{ $cuti->masterCuti->name }} selama {{ $cuti->days_requested }} hari kerja pada tanggal {{ $cuti->start_date->format('d F Y') }} sampai dengan tanggal {{ $cuti->end_date->format('d F Y') }}</p>
            @endif
            <p>Adapun alasan pengajuan cuti saya adalah {{  $cuti->reason }}</p>
        </div>
        <div class="note">
            <p>Demikian permohonan cuti ini saya buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="signature-section">
            <div class="signature-box" style="float: left; width: 50%;">
                
            </div>
            <div class="signature-box" style="float: right; width: 50%;">
                <p>Hormat Saya,</p>
                <p>Yang Mengajukan,</p>
                @if ($cuti->user->attachment_ttd)
                    <img src="{{ asset('storage/' . $cuti->user->attachment_ttd) }}" alt="Signature" style="max-width: 200px; max-height: 100px;">
                    <p style="margin-top: -10px;">{{ $cuti->user->name }}</p> 
                @else
                    <span class="name-underline">{{ $cuti->user->name }}</span>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>
        

        <!-- detail cuti yang sudah diambil dan sisa cuti-->
         
        <div class="cuti-details" style="margin-top: 20px;">
            <p style="font-weight: bold;">Informasi Cuti Karyawan:</p>
            <table>
                <tbody>
                    <tr>
                        <td>Total Hak {{ $cuti->masterCuti->name }} {{ \Carbon\Carbon::now()->format('Y') }}</td>
                        <td>: {{ $jatahCuti ?? 'N/A' }} Hari</td>
                    </tr>
                    <tr>
                        <td>{{ $cuti->masterCuti->name }} yang Sudah Diambil</td>
                        <td>: {{ $report_data['taken_leave_this_year'] - $cuti->days_requested }} Hari</td>
                    </tr>
                    <tr>
                        <td>{{ $cuti->masterCuti->name }} yang diambil saat ini</td>
                        <td>: {{ $cuti->days_requested }} Hari</td>
                    </tr>
                    <tr>
                        <td>Sisa {{ $cuti->masterCuti->name }}</td>
                        <td>: {{ $report_data['remaining_leave'] }} Hari</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        

        <div class="signature-section">
            <div class="signature-box" style="float: left; width: 50%;">
                <p>Mengetahui,</p>
                <p>MANAGER PEOPLE&CULTURES,</p>
                <span class="name-underline" style="margin-top:95px">{{ '.......................' }}</span>
            </div>
            <div class="signature-box" style="float: right; width: 50%;">
                <p>KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI ,</p>
                <p>Atasan Pemohon,</p>
                <span class="name-underline">{{ $report_data['supervisor_name'] ?? 'Dedek Apriyani' }}</span>
            </div>
            <div style="clear: both;"></div>
        </div>
</body>
</html>