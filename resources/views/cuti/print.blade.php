<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Request Details') }}
        </h2>
    </x-slot>

    {{-- STYLE KHUSUS UNTUK DOKUMEN & PRINT --}}
    <style>
        .cuti-doc-body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }
        .cuti-doc-container {
            max-width: 800px;
            margin: auto;
            border: 1px solid #000;
            padding: 15px;
        }
        .cuti-doc-header-section {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .cuti-doc-logo-box {
            width: 15%;
            padding-right: 15px;
            border-right: 2px solid #000;
            padding-right: 10px;
            margin-right: 10px;
        }
        .cuti-doc-company-info {
            width: 85%;
            text-align: left;
        }
        .cuti-doc-company-info p {
            margin-bottom: 0;
            line-height: 1.2;
        }
        .cuti-doc-form-title {
            text-align: center;
            font-weight: bold;
            padding: 5px;
        }
        .cuti-doc-section {
            margin-bottom: 15px;
        }
        .cuti-doc-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .cuti-doc-section td:first-child {
            width: 30%;
            padding-right: 5px;
            vertical-align: top;
        }
        .cuti-doc-section td {
            padding: 1px 0;
        }
        .cuti-doc-cuti-details p {
            margin: 8px 0;
        }
        .cuti-doc-note {
            margin-top: 20px;
            font-style: italic;
        }
        .cuti-doc-signature-section {
            margin-top: 40px;
        }
        .cuti-doc-signature-box {
            width: 50%;
            display: inline-block;
            text-align: center;
        }
        .cuti-doc-name-underline {
            display: block;
            margin-top: 85px;
            text-decoration: underline;
        }
        .cuti-doc-cuti-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .cuti-doc-cuti-details-table td {
            padding: 2px 0;
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
    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- GRID 2 KOLOM --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KIRI: KALENDER + DETAIL PENGAJUAN CUTI --}}
                <div class="space-y-4">
                    {{-- KALENDER CUTI --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Kalender Cuti
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Highlight biru = hari cuti yang diajukan, merah = akhir pekan.
                                </p>
                            </div>
                            <div class="text-right text-xs text-gray-600">
                                <div class="font-semibold">
                                    {{ $cuti->start_date->locale('id')->isoFormat('MMMM Y') }}
                                </div>
                                <div class="text-[10px] text-gray-400">
                                    (berdasarkan tanggal mulai cuti)
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-gray-100">
                            <table class="w-full text-[11px] text-center">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-2 py-1">Sen</th>
                                        <th class="px-2 py-1">Sel</th>
                                        <th class="px-2 py-1">Rab</th>
                                        <th class="px-2 py-1">Kam</th>
                                        <th class="px-2 py-1">Jum</th>
                                        <th class="px-2 py-1">Sab</th>
                                        <th class="px-2 py-1">Min</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @while ($currentCal <= $lastCal)
                                        <tr>
                                            @for ($i = 0; $i < 7; $i++)
                                                @php
                                                    $isCurrentMonth = $currentCal->month === $calendarStart->month;
                                                    $isLeaveDay = $currentCal->between(
                                                        $cuti->start_date->copy()->startOfDay(),
                                                        $cuti->end_date->copy()->endOfDay()
                                                    );
                                                    $isWeekend = $currentCal->isWeekend();
                                                    $isToday   = $currentCal->isToday();
                                                @endphp

                                                <td class="h-8 align-middle
                                                    px-1 py-1
                                                    @if(!$isCurrentMonth) bg-gray-50 text-gray-300
                                                    @else
                                                        @if($isLeaveDay)
                                                            @if($isWeekend)
                                                                bg-yellow-100 text-red-400 font-semibold
                                                            @elseif($holidayDates && in_array($currentCal->toDateString(), $holidayDates))
                                                                bg-red-100 text-red-500 font-semibold
                                                            @else
                                                                bg-indigo-400 text-white font-semibold
                                                            @endif
                                                        @elseif($isWeekend)
                                                            @if($currentCal->isSaturday())
                                                                bg-yellow-100 text-red-400 font-semibold
                                                            @else
                                                                bg-yellow-100 text-red-400 font-semibold
                                                            @endif
                                                        @elseif($holidayDates && in_array($currentCal->toDateString(), $holidayDates))
                                                            bg-red-100 text-red-500 font-semibold
                                                        @else
                                                            bg-white text-gray-800
                                                        @endif
                                                    @endif
                                                ">
                                                    <div class="relative inline-flex items-center justify-center w-6 h-6 rounded-full
                                                        @if($isToday && !$isLeaveDay)
                                                            ring-1 ring-indigo-300
                                                        @endif
                                                    ">
                                                        <span class="text-[11px]">
                                                            {{ $currentCal->day }}
                                                        </span>
                                                    </div>
                                                </td>

                                                @php
                                                    $currentCal->addDay();
                                                @endphp
                                            @endfor
                                        </tr>
                                    @endwhile
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-3 text-[11px] text-gray-600">
                            <div class="inline-flex items-center gap-1">
                                <span class="inline-flex w-3 h-3 rounded-full bg-indigo-600"></span>
                                <span>Hari Cuti diajukan</span>
                            </div>
                            <div class="inline-flex items-center gap-1">
                                <span class="inline-flex w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span>Akhir pekan</span>
                            </div>
                            <div class="inline-flex items-center gap-1">
                                <span class="inline-flex w-3 h-3 rounded-full bg-red-400"></span>
                                <span>Hari Libur Nasional</span>
                            </div>
                            <div class="inline-flex items-center gap-1">
                                <span class="inline-flex w-3 h-3 rounded-full border border-indigo-300"></span>
                                <span>Hari ini</span>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL PENGAJUAN CUTI --}}
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Leave Request #{{ $cuti->id }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Detail informasi pengajuan cuti karyawan.
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 text-[11px] rounded-full
                                @if($cuti->status == 'pending') bg-amber-100 text-amber-700
                                @elseif($cuti->status == 'approved') bg-emerald-100 text-emerald-700
                                @else bg-red-100 text-red-700 @endif">
                                Status: {{ ucfirst($cuti->status) }}
                            </span>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="font-semibold text-gray-700">User:</span>
                                <span class="text-gray-800">{{ $cuti->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Leave Type:</span>
                                <span class="text-gray-800">{{ $cuti->masterCuti->name }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Start Date:</span>
                                <span class="text-gray-800">{{ $cuti->start_date->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">End Date:</span>
                                <span class="text-gray-800">{{ $cuti->end_date->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Days Requested:</span>
                                <span class="text-gray-800">{{ $cuti->days_requested }} Days</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Reason:</span>
                                <span class="text-gray-800">{{ $cuti->reason }}</span>
                            </div>

                            @if ($cuti->processed_by)
                                <div class="pt-3 border-t border-dashed border-gray-200">
                                    <div>
                                        <span class="font-semibold text-gray-700">Processed By:</span>
                                        <span class="text-gray-800">{{ $cuti->processor->name }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-700">Processed At:</span>
                                        <span class="text-gray-800">{{ $cuti->processed_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-2">
                            <a href="{{ route('cuti.index') }}"
                               class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-200 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100">
                                Back to List
                            </a>

                            @if (Auth::user()->hasRole('admin') && $cuti->status == 'pending')
                                <form action="{{ route('cuti.approve', $cuti) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('cuti.reject', $cuti) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700">
                                        Reject
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- KANAN: SURAT PENGAJUAN CUTI + TOMBOL PRINT (TIDAK BERUBAH) --}}
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-4 col-span-2">
                    <div class="flex items-center justify-between mb-3 no-print">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">
                                Surat Pengajuan Cuti (Preview)
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Klik tombol <strong>Print</strong> untuk mencetak hanya formulir ini.
                            </p>
                        </div>
                        <button
                            type="button"
                            onclick="printDocument()"
                            class="inline-flex items-center px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 2a2 2 0 00-2 2v3h12V4a2 2 0 00-2-2H6z" />
                                <path d="M4 9a2 2 0 00-2 2v3h3v3h10v-3h3v-3a2 2 0 00-2-2H4z" />
                            </svg>
                            Print Form
                        </button>
                    </div>

                    {{-- AREA YANG AKAN DI-PRINT --}}
                    <div id="print-area" class="cuti-doc-body">
                        <div class="cuti-doc-container">

                            <div style="padding: 5px; margin-bottom: 5px;">
                                <div class="cuti-doc-header-section">
                                    <div class="cuti-doc-logo-box">
                                        <img src="http://qtasnim.com/wp-content/uploads/2025/03/cropped-Qtasnim-Digital-Teknologi-Logo.png"
                                             alt="Company Logo" style="max-width: 100%;">
                                    </div>
                                    <div class="cuti-doc-company-info">
                                        <p style="font-size: 14pt; font-weight: bold;">PT QTASNIM DIGITAL TEKNOLOGI</p>
                                        <p>Padasuka Ideal Residence Blok A5 No. 11 Bandung Tlp. (022) 20529499, E-mail : office@qtasnim.com</p>
                                    </div>
                                </div>
                            </div>

                            <p class="cuti-doc-form-title" style="margin-top: 0px;">FORMULIR PERMOHONAN CUTI KARYAWAN</p>
                            <div style="text-align: center; margin-bottom: 10px;">
                                <p>Nomor : {{ $report_data['form_number'] }} /Srt-Cuti/QT/{{ $report_data['form_number_month'] }}/{{ \Carbon\Carbon::now()->format('Y') }}</p>
                            </div>

                            <div style="text-align: right; margin-bottom: 15px; margin-right: 20px;">
                                <p>Tanjung Enim, {{ $cuti->created_at->format('d F Y') }}</p>
                            </div>

                            <div class="cuti-doc-section">
                                <p>Kepada Yth,</p>
                                <p>Bapak/Ibu Pimpinan</p>
                                <p>HRD PT QTASNIM DIGITAL TEKNOLOGI</p>
                                <p>Di Tempat</p>
                                <br>
                            </div>

                            <div class="cuti-doc-section">
                                <p>Yang bertanda tangan di bawah ini:</p>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Nama</td>
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
                                    <p style="margin-top: 8px;">
                                        Mengajukan Permohonan {{ $cuti->masterCuti->name }} selama {{ $cuti->days_requested }} hari kerja
                                        pada tanggal {{ $cuti->start_date->format('d F Y') }}.
                                    </p>
                                @else
                                    <p style="margin-top: 8px;">
                                        Mengajukan Permohonan {{ $cuti->masterCuti->name }} selama {{ $cuti->days_requested }} hari kerja
                                        pada tanggal {{ $cuti->start_date->format('d F Y') }} sampai dengan tanggal {{ $cuti->end_date->format('d F Y') }}.
                                    </p>
                                @endif

                                <p>Adapun alasan pengajuan cuti saya adalah {{ $cuti->reason }}.</p>
                            </div>

                            <div class="cuti-doc-note">
                                <p>Demikian permohonan cuti ini saya buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
                            </div>

                            <div class="cuti-doc-signature-section">
                                <div class="cuti-doc-signature-box" style="float: left; text-align: left; width: 50%;">
                                    <div style="margin-top: 20px;">
                                        <p style="font-weight: bold;">Informasi Cuti Karyawan:</p>
                                        <table class="cuti-doc-cuti-details-table">
                                            <tbody>
                                                <tr>
                                                    <td>Total Hak {{ $cuti->masterCuti->name }} {{ $cuti->start_date->format('Y') }}</td>
                                                    <td>: {{ $jatahCuti ?? 'N/A' }} Hari</td>
                                                </tr>
                                                <tr>
                                                    <td>Cuti yang Sudah Diambil</td>
                                                    <td>: {{ $report_data['taken_leave_this_year'] - $cuti->days_requested }} Hari</td>
                                                </tr>
                                                <tr>
                                                    <td>Cuti yang diambil saat ini</td>
                                                    <td>: {{ $cuti->days_requested }} Hari</td>
                                                </tr>
                                                <tr>
                                                    <td>Sisa Cuti</td>
                                                    <td>: {{ $report_data['remaining_leave'] }} Hari</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="cuti-doc-signature-box" style="float: right; width: 50%;">
                                    <p>Hormat Saya,</p>
                                    <p>Yang Mengajukan,</p>
                                    @if ($cuti->user->attachment_ttd)
                                        <img src="{{ asset('storage/' . $cuti->user->attachment_ttd) }}"
                                             alt="Signature" style="max-width: 200px; max-height: 100px; display: block; position: relative; top: -10px; left: 120px;">
                                        <p style="margin-top: -10px;">{{ $cuti->user->name }}</p>
                                    @else
                                        <span class="cuti-doc-name-underline">{{ $cuti->user->name }}</span>
                                    @endif
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                            <div class="cuti-doc-signature-section">
                                <div class="cuti-doc-signature-box" style="float: left; width: 50%;">
                                    <p>Mengetahui,</p>
                                    <p>MANAGER PEOPLE & CULTURES,</p>
                                    <span class="cuti-doc-name-underline" style="margin-top:95px">{{ '.......................' }}</span>
                                </div>
                                <div class="cuti-doc-signature-box" style="float: right; width: 50%;">
                                    <p>KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI,</p>
                                    <p>Atasan Pemohon,</p>
                                    <span class="cuti-doc-name-underline">
                                        {{ $report_data['supervisor_name'] ?? 'Dedek Apriyani' }}
                                    </span>
                                </div>
                                <div style="clear: both;"></div>
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
