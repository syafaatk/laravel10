<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Report Pengajuan Cuti PT. Qtasnim Digital Teknologi</title>
    <!-- dalam bentuk tabel -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- bentuk tabel kolom berisi periode tanggal mulai dan tanggal akhir pencarian, untuk baris semua nama user yang ada, jika dalam tanggal tersebut pegawai itu cuti, maka isi x  -->
    <h1>Report Pengajuan Cuti PT. Qtasnim Digital Teknologi</h1>
    <table>
        <thead>
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="{{ count($dateRange) + 1 }}">Nama Pegawai</th>
                <th rowspan="3">Kontrak</th>
                <th colspan="{{ count($dateRange) }}" style="text-align: center;">Periode: {{ $startDate->format('d F Y') }} sampai {{ $endDate->format('d F Y') }}</th>
                <th rowspan="3">Jumlah Cuti</th>
            </tr>
            <tr>
                @foreach ($dateRange as $date)
                <!-- format indonesia bahasa -->
                    <th>{{ $date->locale('id')->isoFormat('ddd') }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($dateRange as $date)
                    <th>{{ $date->format('d') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->detailKontrakUserActive->kontrak }}</td>
                    @foreach ($dateRange as $date)
                        @if (isset($cutiData[$user->id]) && in_array($date->format('Y-m-d'), $cutiData[$user->id]))
                            @if ($date->isWeekend())
                            <td style="text-align: center; background-color: #e74040ff;">x</td>
                            @else
                            <td style="text-align: center; background-color: #08c952ff;">&#10003;</td>
                            @endif
                        @elseif ($date->isWeekend())
                            <td style="text-align: center; background-color: #e74040ff;">x</td>
                        <!-- get from holidayDates -->
                        @elseif (in_array($date->format('Y-m-d'), $holidayDates))
                            <td style="text-align: center; background-color: #f2d024ff;">x</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                    <td>{{ isset($cutiData[$user->id]) ? count($cutiData[$user->id]) : 0 }} hari</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <!-- legenda warna -->
            <tr>
                <td colspan="{{ count($dateRange) + 4 }}">
                    <strong>Legenda Warna:</strong>
                    <span style="background-color: #08c952ff; padding: 2px 6px; border: 1px solid black;">&#10003; : Hari Cuti</span>
                    <span style="background-color: #e74040ff; padding: 2px 6px; border: 1px solid black;">x : Akhir Pekan (Sabtu & Minggu)</span>
                    <span style="background-color: #f2d024ff; padding: 2px 6px; border: 1px solid black;">x : Hari Libur Nasional</span>
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>