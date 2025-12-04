<x-app-layout>
    <x-slot name="header print:hidden">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Report Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                {{-- Header & periode info --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4 print:hidden">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Rekap Pengajuan Cuti Pegawai
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Periode:
                            <span class="font-medium">
                                {{ $startDate->format('d F Y') }} s/d {{ $endDate->format('d F Y') }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Tanda centang menunjukkan hari cuti yang dihitung, huruf <strong>x</strong> menunjukkan akhir pekan atau hari libur nasional.
                        </p>
                    </div>

                    {{-- Legenda di atas tabel (lebih gampang dilihat) --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <div class="inline-flex items-center gap-1">
                            <span class="inline-flex w-4 h-4 rounded-sm bg-emerald-500 border border-emerald-600"></span>
                            <span>Hari Cuti (&#10003;)</span>
                        </div>
                        <div class="inline-flex items-center gap-1">
                            <span class="inline-flex w-4 h-4 rounded-sm bg-red-500 border border-red-600"></span>
                            <span>Akhir Pekan (x)</span>
                        </div>
                        <div class="inline-flex items-center gap-1">
                            <span class="inline-flex w-4 h-4 rounded-sm bg-amber-400 border border-amber-500"></span>
                            <span>Hari Libur Nasional (x)</span>
                        </div>
                    </div>
                    <!-- tombol print -->
                    <div>
                        <button onclick="window.print()" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                            Print Report
                        </button>
                    </div>
                </div>
                {{-- Tabel responsif --}}
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="min-w-full text-xs md:text-sm text-left print">
                        <thead class="bg-gray-50 text-[11px] uppercase text-gray-600">
                            <tr>
                                <th rowspan="3" class="px-3 py-2 border-b border-gray-200 text-center align-middle">
                                    No
                                </th>
                                <th rowspan="3" class="px-3 py-2 border-b border-gray-200 align-middle">
                                    Nama Pegawai
                                </th>
                                <th rowspan="3" class="px-3 py-2 border-b border-gray-200 align-middle">
                                    Kontrak
                                </th>

                                {{-- Sisa cuti --}}
                                <th colspan="2" class="px-3 py-2 border-b border-gray-200 text-center">
                                    Sisa Cuti
                                </th>

                                {{-- Periode tanggal --}}
                                <th colspan="{{ count($dateRange) }}" class="px-3 py-2 border-b border-gray-200 text-center">
                                    Periode: {{ $startDate->format('d F Y') }} s/d {{ $endDate->format('d F Y') }}
                                </th>

                                <th rowspan="3" class="px-3 py-2 border-b border-gray-200 text-center align-middle">
                                    Jumlah Cuti<br><span class="text-[10px] font-normal text-gray-500">(hari kerja)</span>
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="px-3 py-2 border-b border-gray-200 text-center">
                                    {{ Carbon\Carbon::now()->year }}
                                </th>
                                <th rowspan="2" class="px-3 py-2 border-b border-gray-200 text-center">
                                    {{ Carbon\Carbon::now()->year + 1 }}
                                </th>

                                {{-- Hari (Sen, Sel, ...) --}}
                                @foreach ($dateRange as $date)
                                    <th class="px-1 py-1 border-b border-gray-200 text-center">
                                        {{ $date->locale('id')->isoFormat('ddd') }}
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                {{-- Tanggal (01, 02, ...) --}}
                                @foreach ($dateRange as $date)
                                    <th class="px-1 py-1 border-b border-gray-200 text-center">
                                        {{ $date->format('d') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-center align-top">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-3 py-2 align-top">
                                        <div class="font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 align-top text-gray-700">
                                        {{ $user->detailKontrakUserActive->kontrak }}
                                    </td>

                                    <td class="px-3 py-2 align-top text-center text-gray-800">
                                        {{ $user->sisa_cuti_tahun_ini }} hari
                                    </td>
                                    <td class="px-3 py-2 align-top text-center text-gray-800">
                                        {{ $user->sisa_cuti_tahun_depan }} hari
                                    </td>

                                    {{-- Kolom hari per tanggal --}}
                                    @foreach ($dateRange as $date)
                                        @php
                                            $isCuti = isset($cutiData[$user->id]) &&
                                                      in_array($date->format('Y-m-d'), $cutiData[$user->id]);
                                            $isWeekend = $date->isWeekend();
                                            $isHoliday = in_array($date->format('Y-m-d'), $holidayDates);
                                        @endphp

                                        @if ($isCuti)
                                            @if ($isWeekend)
                                                <td class="px-1 py-1 text-center align-middle text-[11px] font-semibold bg-red-500 text-white">
                                                    x
                                                </td>
                                            @elseif ($isHoliday)
                                                <td class="px-1 py-1 text-center align-middle text-[11px] font-semibold bg-amber-400 text-gray-900">
                                                    x
                                                </td>
                                            @else
                                                <td class="px-1 py-1 text-center align-middle text-[13px] font-semibold bg-emerald-500 text-white">
                                                    &#10003;
                                                </td>
                                            @endif
                                        @elseif ($isWeekend)
                                            <td class="px-1 py-1 text-center align-middle text-[11px] font-semibold bg-red-500 text-white">
                                                x
                                            </td>
                                        @elseif ($isHoliday)
                                            <td class="px-1 py-1 text-center align-middle text-[11px] font-semibold bg-amber-400 text-gray-900">
                                                x
                                            </td>
                                        @else
                                            <td class="px-1 py-1 text-center align-middle text-[11px] text-gray-500">
                                                {{-- hari kerja kosong --}}
                                            </td>
                                        @endif
                                    @endforeach

                                    {{-- Jumlah cuti (abaikan weekend & holiday) --}}
                                    <td class="px-3 py-2 text-center font-semibold text-gray-900">
                                        {{
                                            isset($cutiData[$user->id])
                                                ? count(
                                                    array_filter(
                                                        $cutiData[$user->id],
                                                        function ($cutiDate) use ($startDate, $endDate, $holidayDates) {
                                                            $date = \Carbon\Carbon::parse($cutiDate);
                                                            return $date->between($startDate, $endDate) &&
                                                                   !$date->isWeekend() &&
                                                                   !in_array($date->format('Y-m-d'), $holidayDates);
                                                        }
                                                    )
                                                )
                                                : 0
                                        }} hari
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="{{ count($dateRange) + 6 }}" class="px-3 py-3 text-[11px] md:text-xs text-gray-600">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <strong class="mr-1">Legenda:</strong>
                                        <div class="inline-flex items-center gap-1">
                                            <span class="inline-flex w-4 h-4 rounded-sm bg-emerald-500 border border-emerald-600"></span>
                                            <span>Hari Cuti (&#10003;)</span>
                                        </div>
                                        <div class="inline-flex items-center gap-1">
                                            <span class="inline-flex w-4 h-4 rounded-sm bg-red-500 border border-red-600"></span>
                                            <span>Akhir Pekan (x)</span>
                                        </div>
                                        <div class="inline-flex items-center gap-1">
                                            <span class="inline-flex w-4 h-4 rounded-sm bg-amber-400 border border-amber-500"></span>
                                            <span>Hari Libur Nasional (x)</span>
                                        </div>
                                        <span class="mt-1 md:mt-0 text-[10px] text-gray-400">
                                            Catatan: Jumlah cuti hanya menghitung hari kerja, tidak termasuk akhir pekan & hari libur.
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
