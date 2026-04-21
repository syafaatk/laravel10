<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Curriculum Vitae - ' . $user->name) }}
            </h2>
            <div>
                <a href="{{ route('cv.edit') }}" class="text-sm text-gray-700 underline">Kelola CV</a>
                <a href="{{ route('cv.download') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Download PDF
                </a>
                <button onclick="window.print()" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Cetak
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            .no-print {
                display: none;
            }
            main {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
            .printable-area {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg printable-area" id="cv-content">
                <div class="p-6 md:p-10 text-gray-900">

                    <div class="grid grid-cols-4 gap-8">
                        {{-- KIRI: FOTO & KONTAK --}}
                        <div class="col-span-1">
                            @if($user->attachment_foto_profile)
                                <img src="{{ Storage::url($user->attachment_foto_profile) }}" alt="Foto Profil" class="rounded-full w-32 h-32 mx-auto object-cover mb-4">
                            @else
                                <div class="rounded-full w-32 h-32 mx-auto bg-gray-200 flex items-center justify-center mb-4">
                                    <span class="text-gray-500">No Photo</span>
                                </div>
                            @endif
                            <div class="text-center">
                                <h3 class="font-bold border-b pb-2 mb-2">Kontak</h3>
                                <div class="text-sm space-y-1 text-gray-600 break-words">
                                    <p>{{ $user->email }}</p>
                                    @if($user->no_wa)
                                        <p>{{ $user->no_wa }}</p>
                                    @endif
                                    @if($user->address)
                                        <p>{{ $user->address }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($user->cv && ($user->cv->linkedin_url || $user->cv->github_url || $user->cv->portfolio_url))
                            <div class="text-center mt-6">
                                <h3 class="font-bold border-b pb-2 mb-2">Tautan</h3>
                                <div class="text-sm space-y-1 text-blue-600 hover:text-blue-800 break-words">
                                    @if($user->cv->linkedin_url)
                                        <a href="{{ $user->cv->linkedin_url }}" target="_blank">LinkedIn</a>
                                    @endif
                                    @if($user->cv->github_url)
                                        <a href="{{ $user->cv->github_url }}" target="_blank">GitHub</a>
                                    @endif
                                    @if($user->cv->portfolio_url)
                                        <a href="{{ $user->cv->portfolio_url }}" target="_blank">Portofolio</a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- KANAN: DETAIL CV --}}
                        <div class="col-span-3">

                    {{-- Header CV --}}
                    <div class="border-b-2 border-gray-300 pb-4 mb-6">
                        <h1 class="text-4xl font-bold">{{ $user->name }}</h1>
                        <p class="text-lg text-gray-600 mt-1">{{ $user->jabatan ?? 'Jabatan Belum Diisi' }}</p>
                    </div>

                    {{-- Ringkasan Pribadi --}}
                    @if($user->cv && $user->cv->ringkasan_pribadi)
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold border-b border-gray-200 pb-2 mb-4">Ringkasan Pribadi</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $user->cv->ringkasan_pribadi }}
                        </p>
                    </div>
                    @endif

                    {{-- Pengalaman Kerja --}}
                    @if($user->pengalamanKerjas->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold border-b border-gray-200 pb-2 mb-4">Pengalaman Kerja</h3>
                        <div class="space-y-6">
                            @foreach($user->pengalamanKerjas->sortByDesc('tanggal_mulai') as $pengalaman)
                                @php
                                    $start = \Carbon\Carbon::parse($pengalaman->tanggal_mulai);
                                    $end = $pengalaman->tanggal_selesai ? \Carbon\Carbon::parse($pengalaman->tanggal_selesai) : \Carbon\Carbon::now();
                                    $durasi = $start->diff($end)->format('%y tahun %m bulan');
                                @endphp
                                <div>
                                    <div class="flex justify-between items-baseline">
                                        <h4 class="font-bold text-lg">{{ $pengalaman->posisi }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($pengalaman->tanggal_mulai)->isoFormat('MMMM YYYY') }} - 
                                            {{ $pengalaman->tanggal_selesai ? \Carbon\Carbon::parse($pengalaman->tanggal_selesai)->isoFormat('MMMM YYYY') : 'Sekarang' }}
                                        </p>
                                    </div>
                                    <div class="flex justify-between items-baseline">
                                        <p class="font-semibold text-gray-700">{{ $pengalaman->perusahaan }}</p>
                                        <p class="text-xs text-gray-500">{{ $durasi }}</p>
                                    </div>
                                    @if($pengalaman->deskripsi)
                                        <p class="mt-2 text-sm text-gray-600 leading-normal whitespace-pre-wrap">
                                            {!! nl2br(e($pengalaman->deskripsi)) !!}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Riwayat Pendidikan --}}
                    @if($user->pendidikans->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold border-b border-gray-200 pb-2 mb-4">Pendidikan</h3>
                        <div class="space-y-4">
                             @foreach($user->pendidikans->sortByDesc('tahun_lulus') as $pendidikan)
                                <div>
                                    <div class="flex justify-between items-baseline">
                                        <h4 class="font-bold text-lg">{{ $pendidikan->institusi }}</h4>
                                        <p class="text-sm text-gray-500">{{ $pendidikan->tahun_lulus }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-700">{{ $pendidikan->jenjang }} - {{ $pendidikan->jurusan }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Keahlian --}}
                    @if($user->keahlians->isNotEmpty())
                    <div>
                        <h3 class="text-xl font-semibold border-b border-gray-200 pb-2 mb-4">Keahlian</h3>
                        <div class="flex flex-wrap gap-4">
                            @foreach($user->keahlians as $keahlian)
                                <div class="w-1/2 md:w-1/3">
                                    <p class="font-semibold">{{ $keahlian->nama_keahlian }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                        @php
                                            $width = '25%';
                                            if ($keahlian->tingkat == 'Menengah') $width = '50%';
                                            if ($keahlian->tingkat == 'Mahir') $width = '75%';
                                            if ($keahlian->tingkat == 'Ahli') $width = '100%';
                                        @endphp
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $width }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>