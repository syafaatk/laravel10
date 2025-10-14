<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Klaim Reimburse</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 15px;
        }
        .applicant-info, .reimburse-details, .bank-info {
            margin-bottom: 15px;
        }
        .applicant-info table, .bank-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .applicant-info td:first-child, .bank-info td:first-child {
            width: 25%;
            padding-right: 5px;
            vertical-align: top;
        }
        .applicant-info td, .bank-info td {
            padding: 1px 0;
        }

        /* Reimbursement Details Table */
        .reimburse-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .reimburse-table th, .reimburse-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .reimburse-table th {
            text-align: center;
            background-color: #f2f2f2;
        }
        .reimburse-table .project-row td {
            border-top: none;
            border-bottom: none;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 0;
        }
        .reimburse-table .sub-total-row td {
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
            margin-top: 60px; /* Space for signature */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">

        <div style="border: 1px solid #000; padding: 15px; margin-bottom: 15px;">
            <div class="header-section">
                <div class="logo-box" style="border-right: 2px solid #000; padding-right: 10px; margin-right: 10px;">
                     <img src="http://qtasnim.com/wp-content/uploads/2025/03/cropped-Qtasnim-Digital-Teknologi-Logo.png" alt="Company Logo" style="max-width: 100%;">
                </div>
                <div class="company-info">
                    <p style="font-size: 14pt; font-weight: bold;">PT QTASNIM DIGITAL TEKNOLOGI</p>
                    <p>Padasuka Ideal Residence Blok A5 No. 11 Bandung Tlp. (022) 20529499, E-mail : office@qtasnim.com</p>
                    
                </div>
                
            </div>
            <p class="form-title">FORMULIR KLAIM REIMBURSE TRANSPORT DAN AKOMODASI WAKAF</p>
        </div>
        <div class="form-number" style="text-align: center; margin-bottom: 10px;">
            <p>Nomor : .... /FM-KR/QT/{{ $report_data['form_number_month'] }}/{{ \Carbon\Carbon::now()->format('Y') }}</p>
        </div>
        <div class="applicant-info">
            <p>Yang bertanda tangan di bawah ini:</p>
            <table>
                <tbody>
                    <tr>
                        <td>Nama</td>
                        <!-- user login -->
                        <td>: {{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td>
                        <td>: TANJUNG ENIM</td>
                    </tr>
                </tbody>
            </table>
            <p>Mengajukan permohonan Reimburse dengan rincian:</p>
        </div>

        <div class="reimburse-details">
            <p style="font-weight: bold;">Reimburse Project: Digitalisasi - K3 - SCMS</p>
            <p style="font-weight: bold; padding-left: 20px;">ADMINISTRASI</p>

            <table class="reimburse-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Tanggal Klaim</th>
                        <th style="width: 75%;">{{ \Carbon\Carbon::now()->format('d F Y') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="height: 120px;">
                            <p style="margin-bottom: 0;">Detail Pengeluaran</p>
                        </td>
                        <td>
                            <ol type="A" style="padding-left: 20px; margin-bottom: 0;">
                                <!-- per tipe : -->
                                Tipe Reimburse:
                                @php
                                    $reimbursementsByTipe = $reimbursements->groupBy('tipe');
                                @endphp
                                @foreach ($reimbursementsByTipe as $tipe => $reimbursementGroup)
                                    <li style="font-weight: bold; margin-bottom: 0;">@if($tipe == 1) {{ ' (Transport)' }}@elseif($tipe == 2) {{ ' (Makan-Makan)' }} @else {{ ' (Lain-Lain)' }} @endif - Rp {{ number_format($reimbursementGroup->sum('amount'), 2, ',', '.') }}</li>
                                    <ol style="padding-left: 20px; margin-bottom: 0;">
                                        @foreach ($reimbursementGroup as $reimbursement)
                                            <li>{{ $reimbursement->title }} - Rp {{ number_format($reimbursement->amount, 2, ',', '.') }}</li>
                                        @endforeach
                                    </ol>
                                @endforeach
                                
                            </ol>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="border: 1px solid #000; border-top: none; padding: 5px;">
                <p style="font-weight: bold; margin-bottom: 5px;">ADMINISTRASI UNTUK PENGGANTIAN KLAIM</p>
                <p style="margin-bottom: 0;">Jumlah yang diklaim: 
                    <span style="font-weight: bold;">Rp {{ number_format($reimbursements->sum('amount'), 2, ',', '.') }}</span>
                </p>
                <!-- terbilang -->
                 
                <p style="margin-bottom: 0;">(Terbilang: <span style="font-weight: bold;">{{ $report_data['terbilang_total_amount'] }} Rupiah</span>)</p>
                
            </div>
        </div>

        <div class="bank-info">
            <p class="note">Mohon dipastikan bahwa jumlah yang diklaim sesuai dengan kwitansi</p>
            <p>Transfer bank: (Rincian data akan diperlukan bila sebelumnya tidak disebutkan di formulir permohonan)</p>
            <table>
                <tbody>
                    <tr>
                        <td>No. Rekening Bank</td>
                        <td>: 1120021670877 (Mandiri)</td>
                    </tr>
                    <tr>
                        <td>Atas Nama</td>
                        <td>: Khoirusy Syafaat</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="signature-section">
            <div class="row">
                <div class="col-6 signature-box">
                    <p style="margin-top: 20px; margin-bottom: 0;">Mengetahui oleh,</p>
                    <p style="margin-top: 0;">SM Operation & Finance,</p>
                    <p style="margin-top: 60px;">_________________________</p>
                    <p class="name">Manajer Keuangan</p>
                </div>
                <div class="col-6 signature-box">
                    <div class="date-loc">
                        <p>Tanjung Enim, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                    </div>
                    <p>Pemohon,</p>
                    @if(Auth::user()->attachment_ttd)
                        <img src="{{ asset('storage/' . Auth::user()->attachment_ttd) }}" alt="Signature" style="max-width: 100px;">
                    @else
                        <p style="margin-top: 60px;">_________________________</p>
                    @endif
                    <p class="name">({{  Auth::user()->name }})</p>
                </div>
            </div>
        </div>
        <div class="signature-section">
            <div class="row">
                <div class="col-6 signature-box">
                    <p style="margin-top: 20px; margin-bottom: 0;">Persetujuan,</p>
                    <p style="margin-top: 0;">Direktur,</p>
                    <p style="margin-top: 60px;">_________________________</p>
                    <p class="name">...........</p>
                </div>
                <div class="col-6 signature-box">
                    <p>Pengajuan sebesar Rp {{ number_format($reimbursements->sum('amount'), 2, ',', '.') }}</p><p>Disetujui / Tidak disetujui *),</p>
                </div>
            </div>
        </div>
        <p style="margin-top: 40px; font-style: italic;">*) Coret yang tidak perlu</p>

        <div class="attachment-section">
            <p><strong>Lampiran:</strong></p>
            <table class="reimburse-table">
            <thead>
                <tr>
                <th>No</th>
                <th>Deskripsi - Jumlah - Foto</th>
                <th>Foto Bukti</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reimbursements as $index => $reimbursement)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <p>{{ $reimbursement->created_at->format('d F Y, H:i')}} - {{ $reimbursement->title }} - Rp {{ number_format($reimbursement->amount, 2, ',', '.') }} - {{ $reimbursement->user->name }}</p>
                        <p class="mt-3"><strong>Foto Nota:</strong></p>
                        <img src="{{ asset('storage/' . $reimbursement->attachment) }}" alt="Attachment" class="img-fluid mt-2" style="max-width: 300px;">
                    </td>
                    <td>
                        @if ($reimbursement->attachment_note)
                            <p class="mt-3"><strong>Foto Bukti:</strong></p>
                            <img src="{{ asset('storage/' . $reimbursement->attachment_note) }}" alt="Foto Bukti" class="img-fluid mt-2" style="max-width: 350px;">
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>

        
    </div>
</body>
</html>