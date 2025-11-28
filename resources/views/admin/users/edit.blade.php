<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-900">
                {{ __('Edit User Profile') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- PHOTO & SIGNATURE SECTION --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Profile Photo -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-600">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                            Foto Profile
                        </h3>
                        
                        <div class="mb-4">
                            @if ($user->attachment_foto_profile)
                                <img src="{{ asset('storage/' . $user->attachment_foto_profile) }}" alt="Profile" class="w-full h-40 object-cover rounded-lg border-2 border-gray-200">
                            @else
                                <div class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">No photo</span>
                                </div>
                            @endif
                        </div>

                        <label class="block">
                            <span class="sr-only">Choose photo</span>
                            <input type="file" name="attachment_foto_profile" id="attachment_foto_profile" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                        </label>
                        @error('attachment_foto_profile')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-indigo-600">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            Tanda Tangan
                        </h3>
                        
                        <div class="mb-4">
                            @if ($user->attachment_ttd)
                                <img src="{{ asset('storage/' . $user->attachment_ttd) }}" alt="Signature" class="w-full h-40 object-cover rounded-lg border-2 border-gray-200">
                            @else
                                <div class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">No signature</span>
                                </div>
                            @endif
                        </div>

                        <label class="block">
                            <span class="sr-only">Choose signature</span>
                            <input type="file" name="attachment_ttd" id="attachment_ttd" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                        </label>
                        @error('attachment_ttd')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User Status -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-lg p-6 border-l-4 border-green-600">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 7a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/></svg>
                            Status Pengguna
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Roles</p>
                                <select name="roles[]" id="roles" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" multiple required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->name, $user->roles->pluck('name')->toArray()) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Terdaftar</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BASIC INFORMATION --}}
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No Pegawai -->
                        <div>
                            <label for="nopeg" class="block text-sm font-semibold text-gray-700 mb-2">No Pegawai</label>
                            <input type="number" name="nopeg" id="nopeg" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nopeg') border-red-500 @enderror" value="{{ old('nopeg', $user->nopeg) }}">
                            @error('nopeg')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="no_wa" class="block text-sm font-semibold text-gray-700 mb-2">No WhatsApp</label>
                            <input type="text" name="no_wa" id="no_wa" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_wa') border-red-500 @enderror" value="{{ old('no_wa', $user->no_wa) }}" placeholder="62812345678">
                            @error('no_wa')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jabatan') border-red-500 @enderror">
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="Web Developer" {{ old('jabatan', $user->jabatan) == 'Web Developer' ? 'selected' : '' }}>Web Developer</option>
                                <option value="Mobile Developer" {{ old('jabatan', $user->jabatan) == 'Mobile Developer' ? 'selected' : '' }}>Mobile Developer</option>
                                <option value="System Analyst" {{ old('jabatan', $user->jabatan) == 'System Analyst' ? 'selected' : '' }}>System Analyst</option>
                                <option value="UI UX Designer" {{ old('jabatan', $user->jabatan) == 'UI UX Designer' ? 'selected' : '' }}>UI UX Designer</option>
                                <option value="Technical Writer" {{ old('jabatan', $user->jabatan) == 'Technical Writer' ? 'selected' : '' }}>Technical Writer</option>
                                <option value="IoT Developer" {{ old('jabatan', $user->jabatan) == 'IoT Developer' ? 'selected' : '' }}>IoT Developer</option>
                            </select>
                            @error('jabatan')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kontrak -->
                        <div>
                            <label for="kontrak" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kontrak</label>
                            <select name="kontrak" id="kontrak" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kontrak') border-red-500 @enderror">
                                <option value="">-- Pilih Kontrak --</option>
                                <option value="Join Development PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'Join Development PT Bukit Asam' ? 'selected' : '' }}>Join Development PT Bukit Asam</option>
                                <option value="K3L PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'K3L PT Bukit Asam' ? 'selected' : '' }}>K3L PT Bukit Asam</option>
                                <option value="Supply Chain PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'Supply Chain PT Bukit Asam' ? 'selected' : '' }}>Supply Chain PT Bukit Asam</option>
                                <option value="SDMO PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'SDMO PT Bukit Asam' ? 'selected' : '' }}>SDMO PT Bukit Asam</option>
                            </select>
                            @error('kontrak')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Masuk -->
                        <div>
                            <label for="tgl_masuk" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" id="tgl_masuk" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tgl_masuk') border-red-500 @enderror" value="{{ old('tgl_masuk', $user->tgl_masuk ? $user->tgl_masuk->format('Y-m-d') : '') }}">
                            @error('tgl_masuk')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="address" id="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- PERSONAL INFORMATION --}}
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z"/></svg>
                        Informasi Personal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Motor -->
                        <div>
                            <label for="motor" class="block text-sm font-semibold text-gray-700 mb-2">Motor / Kendaraan</label>
                            <input type="text" name="motor" id="motor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('motor') border-red-500 @enderror" value="{{ old('motor', $user->motor) }}" placeholder="Contoh: Toyota Avanza">
                            @error('motor')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ukuran Baju -->
                        <div>
                            <label for="ukuran_baju" class="block text-sm font-semibold text-gray-700 mb-2">Ukuran Baju</label>
                            <select name="ukuran_baju" id="ukuran_baju" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ukuran_baju') border-red-500 @enderror">
                                <option value="">-- Pilih Ukuran --</option>
                                <option value="XS" {{ old('ukuran_baju', $user->ukuran_baju) == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('ukuran_baju', $user->ukuran_baju) == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('ukuran_baju', $user->ukuran_baju) == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('ukuran_baju', $user->ukuran_baju) == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('ukuran_baju', $user->ukuran_baju) == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('ukuran_baju', $user->ukuran_baju) == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                            @error('ukuran_baju')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- BANKING INFORMATION --}}
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                        Informasi Perbankan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bank -->
                        <div>
                            <label for="bank" class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank</label>
                            <input type="text" name="bank" id="bank" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bank') border-red-500 @enderror" value="{{ old('bank', $user->bank) }}" placeholder="BRI, BCA, Mandiri, dll">
                            @error('bank')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No Rekening -->
                        <div>
                            <label for="norek" class="block text-sm font-semibold text-gray-700 mb-2">No Rekening</label>
                            <input type="text" name="norek" id="norek" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('norek') border-red-500 @enderror" value="{{ old('norek', $user->norek) }}" placeholder="1234567890">
                            @error('norek')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SALARY INFORMATION --}}
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8.5 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm6 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM9 6.5a3 3 0 11-6 0 3 3 0 016 0zm6 0a3 3 0 11-6 0 3 3 0 016 0zM12.5 16a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                        Informasi Gaji & Tunjangan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Gaji Pokok -->
                        <div>
                            <label for="gaji_pokok" class="block text-sm font-semibold text-gray-700 mb-2">Gaji Pokok</label>
                            <input type="number" name="gaji_pokok" id="gaji_pokok" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji_pokok') border-red-500 @enderror" value="{{ old('gaji_pokok', $user->gaji_pokok) }}" placeholder="5000000">
                            @error('gaji_pokok')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tunjangan Tetap -->
                        <div>
                            <label for="gaji_tunjangan_tetap" class="block text-sm font-semibold text-gray-700 mb-2">Tunjangan Tetap</label>
                            <input type="number" name="gaji_tunjangan_tetap" id="gaji_tunjangan_tetap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji_tunjangan_tetap') border-red-500 @enderror" value="{{ old('gaji_tunjangan_tetap', $user->gaji_tunjangan_tetap) }}" placeholder="500000">
                            @error('gaji_tunjangan_tetap')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tunjangan Makan -->
                        <div>
                            <label for="gaji_tunjangan_makan" class="block text-sm font-semibold text-gray-700 mb-2">Tunjangan Makan</label>
                            <input type="number" name="gaji_tunjangan_makan" id="gaji_tunjangan_makan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji_tunjangan_makan') border-red-500 @enderror" value="{{ old('gaji_tunjangan_makan', $user->gaji_tunjangan_makan) }}" placeholder="200000">
                            @error('gaji_tunjangan_makan')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tunjangan Transport & Tempat Tinggal -->
                        <div>
                            <label for="gaji_tunjangan_transport" class="block text-sm font-semibold text-gray-700 mb-2">Tunjangan Transport + Tempat Tinggal</label>
                            <input type="number" name="gaji_tunjangan_transport" id="gaji_tunjangan_transport" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji_tunjangan_transport') border-red-500 @enderror" value="{{ old('gaji_tunjangan_transport', $user->gaji_tunjangan_transport) }}" placeholder="1000000">
                            @error('gaji_tunjangan_transport')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tunjangan Lain-Lain -->
                        <div>
                            <label for="gaji_tunjangan_lain" class="block text-sm font-semibold text-gray-700 mb-2">Tunjangan Lain-Lain</label>
                            <input type="number" name="gaji_tunjangan_lain" id="gaji_tunjangan_lain" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji_tunjangan_lain') border-red-500 @enderror" value="{{ old('gaji_tunjangan_lain', $user->gaji_tunjangan_lain) }}" placeholder="300000">
                            @error('gaji_tunjangan_lain')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- BPJS -->
                        <div>
                            <label for="gaji_bpjs" class="block text-sm font-semibold text-gray-700 mb-2">BPJS</label>
                            <input type="number" name="gaji_bpjs" id="gaji_bpjs" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gaji_bpjs') border-red-500 @enderror" value="{{ old('gaji_bpjs', $user->gaji_bpjs) }}" placeholder="100000">
                            @error('gaji_bpjs')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Total Summary -->
                    @php
                        $total = ($user->gaji_pokok ?? 0) + ($user->gaji_tunjangan_tetap ?? 0) + ($user->gaji_tunjangan_makan ?? 0) + ($user->gaji_tunjangan_transport ?? 0) + ($user->gaji_tunjangan_lain ?? 0) + ($user->gaji_bpjs ?? 0);
                    @endphp
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Total Gaji & Tunjangan</p>
                            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex gap-4 justify-end mb-8">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function() {
            $('#roles').select2({
                placeholder: 'Pilih roles',
                allowClear: true
            });
        });
    </script>
    @endpush
</x-app-layout>