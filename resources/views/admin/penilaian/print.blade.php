<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Penilaian Kinerja Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            border: 1px solid #000;
            padding: 15px;
        }
        .header-section {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
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
        .employee-info, .assessment-details, .summary {
            margin-bottom: 15px;
        }
        .employee-info table, .assessment-details table, .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .employee-info td:first-child, .assessment-details td:first-child, .summary td:first-child {
            padding-right: 5px;
            vertical-align: top;
        }
        .employee-info td, .assessment-details td, .summary td {
            padding: 1px 0;
        }
        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .assessment-table th, .assessment-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            text-align: center;
        }
        .assessment-table th {
            background-color: #f2f2f2;
        }
        .notes {
            margin-top: 20px;
            font-style: italic;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
        }
        .signature-box {
            width: 30%;
            text-align: center;
        }
        .signature-box .name-underline {
            display: block;
            margin-top: 70px;
            text-decoration: underline;
        }
        p {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <div class="logo-box" style="border-right: 2px solid #000; padding-right: 10px; margin-right: 10px;">
                <img src="http://qtasnim.com/wp-content/uploads/2025/03/cropped-Qtasnim-Digital-Teknologi-Logo.png" alt="Company Logo" style="max-width: 100%;">
            </div>
            <div class="company-info">
                <p style="font-size: 14pt; font-weight: bold;">PT QTASNIM DIGITAL TEKNOLOGI</p>
                <p>Padasuka Ideal Residence Blok A5 No. 11 Bandung Tlp. (022) 20529499, E-mail : office@qtasnim.com</p>
            </div>
        </div>
        <p class="form-title">FORMULIR PENILAIAN KINERJA KARYAWAN</p>
        <div class="form-number" style="text-align: center;">
            <p>Nomor : .... /FKK/QT/{{ $report_data['form_number_month'] }}/{{ \Carbon\Carbon::now()->format('Y') }}</p>
        </div>

        <div class="employee-info">
            <p><b>Informasi Karyawan:</b></p>
            <table>
                <tbody>
                    <tr>
                        <td>Nama</td>
                        <td>: {{ $penilaian->user->name }}</td>
                    </tr>
                    <tr>
                        <td>No Pegawai</td>
                        <td>: {{ $penilaian->user->nopeg }}</td>
                    </tr>
                    <tr>
                        <td>Unit Kerja</td>
                        <td>: TANJUNG ENIM</td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td>: {{ $penilaian->user->kontrak }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>: {{ $penilaian->user->jabatan }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Penilaian</td>
                        <td>: {{ $penilaian->review_date->format('d F Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="assessment-details">
            <p><b>Detail Penilaian:</b></p>
            <table class="assessment-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Kriteria Penilaian</th>
                        <th width="15%">Nilai (1-5)</th>
                        <th width="40%">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalBobot = 0;
                        $totalSubtotal = 0;
                    @endphp
                    @foreach ($penilaian->scores as $score)
                        <tr>
                            <td width="5%">{{ $loop->iteration }}</td>
                            <td width="40%">{{ $score['criteria_name'] }}</td>
                            <td width="15%">{{ $score['score_value'] }}</td>
                            <td width="40%">
                                @if ($score['score_value'] >= 5)
                                    Sangat Baik
                                @elseif ($score['score_value'] >= 4)
                                    Baik
                                @elseif ($score['score_value'] >= 3)
                                    Cukup
                                @elseif ($score['score_value'] >= 2)
                                    Kurang
                                @else
                                    Sangat Kurang
                                @endif
                            </td>
                        </tr>
                        @php
                            $totalBobot += 5; // Setiap kriteria memiliki bobot 1
                            $totalSubtotal += $score['score_value'];
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th>{{ $totalSubtotal }} / {{ $totalBobot }}</th>
                        <th>
                            @if ($totalSubtotal >= 50)
                                Sangat Baik
                            @elseif ($totalSubtotal >= 40)
                                Baik
                            @elseif ($totalSubtotal >= 30)
                                Cukup
                            @elseif ($totalSubtotal >= 20)
                                Kurang
                            @else
                                Sangat Kurang
                            @endif
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="summary">
            <p><b>Kesimpulan:</b></p>
            <table>
                <tbody>
                    <tr>
                        <td>Nilai Akhir</td>
                        <td>: {{ $totalSubtotal }}</td>
                    </tr>
                    <tr>
                        <td>Kategori Penilaian</td>
                        <td>: {{ $penilaian->kategori }}</td>
                    </tr>
                    <tr>
                        <td>Strength</td>
                        <td>: {{ $penilaian->strengths }}</td>
                    </tr>
                    <tr>
                        <td>Weakness</td>
                        <td>: {{ $penilaian->weaknesses }}</td>
                    </tr>
                    <tr>
                        <td>Feedback</td>
                        <td>: {{ $penilaian->feedback }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- strength, weekness, feedback -->
         

        <div class="notes">
            <p>Catatan: Formulir ini bersifat rahasia dan hanya digunakan untuk keperluan internal perusahaan.</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Dinilai oleh,</p>
                <p>Atasan Langsung,</p>
                <span class="name-underline">{{ $report_data['supervisor_name'] ?? 'Dedek Apriyani' }}</span>
            </div>
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p>HR Department,</p>
                <span class="name-underline">.......................</span>
            </div>
            <div class="signature-box">
                <p>Disetujui oleh,</p>
                <p>Manajemen,</p>
                <span class="name-underline">.......................</span>
            </div>
        </div>
    </div>
</body>
</html>
