<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Curriculum Vitae (CV)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan Pribadi --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">Ringkasan Pribadi</h2>
                    <p class="mt-1 text-sm text-gray-600">Perbarui ringkasan profesional, deskripsi diri, dan tautan relevan Anda.</p>
                    <form method="post" action="{{ route('cv.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="ringkasan_pribadi" :value="__('Ringkasan Pribadi')" />
                            <textarea id="ringkasan_pribadi" name="ringkasan_pribadi" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('ringkasan_pribadi', $user->cv->ringkasan_pribadi ?? '') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('ringkasan_pribadi')" />
                        </div>

                        <div>
                            <x-input-label for="linkedin_url" :value="__('URL LinkedIn')" />
                            <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" :value="old('linkedin_url', $user->cv->linkedin_url ?? '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
                        </div>

                        <div>
                            <x-input-label for="github_url" :value="__('URL GitHub')" />
                            <x-text-input id="github_url" name="github_url" type="url" class="mt-1 block w-full" :value="old('github_url', $user->cv->github_url ?? '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
                        </div>

                        <div>
                            <x-input-label for="portfolio_url" :value="__('URL Portofolio')" />
                            <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="mt-1 block w-full" :value="old('portfolio_url', $user->cv->portfolio_url ?? '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('portfolio_url')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Riwayat Pendidikan --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h2 class="text-lg font-medium text-gray-900">Riwayat Pendidikan</h2>
                    <div class="mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institusi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Lulus</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($user->pendidikans as $pendidikan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendidikan->jenjang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendidikan->institusi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendidikan->jurusan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pendidikan->tahun_lulus }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('cv.pendidikan.destroy', $pendidikan->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pendidikan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <form method="post" action="{{ route('cv.pendidikan.store') }}" class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        @csrf
                        <div>
                            <x-input-label for="jenjang" :value="__('Jenjang')" />
                            <x-text-input id="jenjang" name="jenjang" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="institusi" :value="__('Institusi')" />
                            <x-text-input id="institusi" name="institusi" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="jurusan" :value="__('Jurusan')" />
                            <x-text-input id="jurusan" name="jurusan" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="tahun_lulus" :value="__('Tahun Lulus')" />
                            <x-text-input id="tahun_lulus" name="tahun_lulus" type="number" placeholder="YYYY" class="mt-1 block w-full" required />
                        </div>
                        <x-primary-button>{{ __('Tambah') }}</x-primary-button>
                    </form>
                </div>
            </div>

            {{-- Pengalaman Kerja --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h2 class="text-lg font-medium text-gray-900">Pengalaman Kerja</h2>
                     <div class="mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($user->pengalamanKerjas as $pengalaman)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pengalaman->perusahaan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $pengalaman->posisi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ \Carbon\Carbon::parse($pengalaman->tanggal_mulai)->format('M Y') }} - 
                                            {{ $pengalaman->tanggal_selesai ? \Carbon\Carbon::parse($pengalaman->tanggal_selesai)->format('M Y') : 'Sekarang' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">{{ $pengalaman->deskripsi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('cv.pengalaman.destroy', $pengalaman->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pengalaman kerja.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <form method="post" action="{{ route('cv.pengalaman.store') }}" class="mt-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="perusahaan" :value="__('Perusahaan')" />
                                <x-text-input id="perusahaan" name="perusahaan" type="text" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="posisi" :value="__('Posisi')" />
                                <x-text-input id="posisi" name="posisi" type="text" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                                <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai (Kosongkan jika masih bekerja)')" />
                                <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Pekerjaan')" />
                            <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                        </div>
                        <x-primary-button>{{ __('Tambah Pengalaman') }}</x-primary-button>
                    </form>
                </div>
            </div>

            {{-- Keahlian --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h2 class="text-lg font-medium text-gray-900">Keahlian</h2>
                    <div class="mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Keahlian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($user->keahlians as $keahlian)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $keahlian->nama_keahlian }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($keahlian->tingkat == 'Ahli') bg-red-100 text-red-800
                                                @elseif($keahlian->tingkat == 'Mahir') bg-blue-100 text-blue-800
                                                @elseif($keahlian->tingkat == 'Menengah') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $keahlian->tingkat }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('cv.keahlian.destroy', $keahlian->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data keahlian.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <form method="post" action="{{ route('cv.keahlian.store') }}" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        @csrf
                        <div>
                            <x-input-label for="nama_keahlian" :value="__('Nama Keahlian')" />
                            <x-text-input id="nama_keahlian" name="nama_keahlian" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="tingkat" :value="__('Tingkat')" />
                            <select id="tingkat" name="tingkat" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option>Dasar</option>
                                <option>Menengah</option>
                                <option>Mahir</option>
                                <option>Ahli</option>
                            </select>
                        </div>
                        <x-primary-button>{{ __('Tambah Keahlian') }}</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <a href="{{ route('cv.show') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Lihat Pratinjau CV
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>