<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Gaji Periode: ') }} <span class="text-indigo-600">{{ $gaji->periode_bulan }}</span>
            </h2>
            <a href="{{ route('admin.gaji.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                &larr; Kembali ke Daftar Periode
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Summary Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rentang Periode</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $gaji->rentang_mulai->format('d M Y') }} &mdash; {{ $gaji->rentang_selesai->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jumlah Karyawan</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $gaji->slipGaji->count() }} Orang</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @php
                                    $statusClass = match($gaji->status) {
                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                        'terkunci' => 'bg-gray-200 text-gray-800',
                                        'dibayar' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($gaji->status) }}
                                </span>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employee List Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                        Daftar Slip Gaji Karyawan
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Karyawan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jabatan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Penghasilan Netto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($gaji->slipGaji as $slip)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ $slip->user->attachment_foto_profile ? asset('storage/' . $slip->user->attachment_foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($slip->user->name).'&color=7F9CF5&background=EBF4FF' }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $slip->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $slip->user->nopeg ? 'No. Peg: ' . $slip->user->nopeg : '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $slip->user->jabatan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($slip->penghasilan_netto, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('slip-gaji.show', $slip->id) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Slip</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Tidak ada data slip gaji untuk periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($gaji->slipGaji->count() > 0)
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <th colspan="3" scope="row" class="px-6 py-3 text-right text-sm font-medium text-gray-700 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                        Rp {{ number_format($gaji->slipGaji->sum('penghasilan_netto'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>