<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Periode Gaji Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.gaji.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="periode_bulan" class="block text-sm font-medium text-gray-700">Periode Bulan</label>
                                <input type="text" name="periode_bulan" id="periode_bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('periode_bulan') border-red-500 @enderror" value="{{ old('periode_bulan') }}" placeholder="Contoh: Januari 2026" required>
                                @error('periode_bulan')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    Nama periode yang akan ditampilkan pada slip gaji.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="rentang_mulai" class="block text-sm font-medium text-gray-700">Rentang Mulai</label>
                                    <input type="date" name="rentang_mulai" id="rentang_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('rentang_mulai') border-red-500 @enderror" value="{{ old('rentang_mulai') }}" required>
                                    @error('rentang_mulai')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="rentang_selesai" class="block text-sm font-medium text-gray-700">Rentang Selesai</label>
                                    <input type="date" name="rentang_selesai" id="rentang_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('rentang_selesai') border-red-500 @enderror" value="{{ old('rentang_selesai') }}" required>
                                    @error('rentang_selesai')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Rentang tanggal digunakan untuk menentukan periode kerja yang digaji. Contoh: untuk gaji <strong>Januari 2026</strong>, rentangnya bisa dari <strong>22 Desember 2025</strong> hingga <strong>21 Januari 2026</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-5">
                            <a href="{{ route('admin.gaji.index') }}" class="text-sm font-medium text-gray-700 mr-4">Batal</a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Buat Periode & Generate Slip Gaji
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>