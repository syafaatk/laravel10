<section class="space-y-8">
    <header class="pb-6 border-b border-gray-200">
        <h2 class="text-3xl font-bold text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-2 text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- PHOTO & SIGNATURE SECTION --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Photo -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-md p-6 border border-blue-200">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                    <h3 class="font-semibold text-gray-900">{{ __('Profile Photo') }}</h3>
                </div>
                
                <div class="mb-4">
                    @if ($user->attachment_foto_profile)
                        <img id="fotoPreview" src="{{ asset('storage/' . $user->attachment_foto_profile) }}" alt="Profile" class="w-full h-48 object-cover rounded-lg border-2 border-white shadow-md">
                    @else
                        <div class="w-full h-48 bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span class="text-gray-500 text-sm">{{ __('No photo uploaded') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <label class="relative block cursor-pointer">
                    <span class="sr-only">{{ __('Choose photo') }}</span>
                    <input type="file" name="attachment_foto_profile" id="attachment_foto_profile" class="hidden" accept="image/*" onchange="previewImage(this, 'fotoPreview')">
                    <div class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Upload Photo') }}
                    </div>
                </label>
                <x-input-error class="mt-2" :messages="$errors->get('attachment_foto_profile')" />
            </div>

            <!-- Tanda Tangan -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg shadow-md p-6 border border-indigo-200">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                    <h3 class="font-semibold text-gray-900">{{ __('Signature') }}</h3>
                </div>
                
                <div class="mb-4">
                    @if ($user->attachment_ttd)
                        <img id="ttdPreview" src="{{ asset('storage/' . $user->attachment_ttd) }}" alt="Signature" class="w-full h-48 object-cover rounded-lg border-2 border-white shadow-md">
                    @else
                        <div class="w-full h-48 bg-white rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span class="text-gray-500 text-sm">{{ __('No signature uploaded') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <label class="relative block cursor-pointer">
                    <span class="sr-only">{{ __('Choose signature') }}</span>
                    <input type="file" name="attachment_ttd" id="attachment_ttd" class="hidden" accept="image/*" onchange="previewImage(this, 'ttdPreview')">
                    <div class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Upload Signature') }}
                    </div>
                </label>
                <x-input-error class="mt-2" :messages="$errors->get('attachment_ttd')" />
            </div>

            <!-- Status Summary -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-md p-6 border border-green-200">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <h3 class="font-semibold text-gray-900">{{ __('Account Status') }}</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="pb-3 border-b border-green-200">
                        <p class="text-xs uppercase font-semibold text-gray-600">{{ __('Registered Since') }}</p>
                        <p class="text-lg font-bold text-green-700 mt-1">{{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    @if ($user->tgl_masuk)
                    <div>
                        <p class="text-xs uppercase font-semibold text-gray-600">{{ __('Join Date') }}</p>
                        <p class="text-lg font-bold text-green-700 mt-1">{{ \Carbon\Carbon::parse($user->tgl_masuk)->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- BASIC INFORMATION --}}
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                {{ __('Basic Information') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Full Name')" class="font-semibold" />
                    <x-text-input id="name" name="name" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="font-semibold" />
                    <x-text-input id="email" name="email" type="email" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="inline underline text-yellow-900 hover:text-yellow-700 font-semibold">
                                {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-semibold text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- No Pegawai -->
                <div>
                    <x-input-label for="nopeg" :value="__('Employee No')" class="font-semibold" />
                    <x-text-input id="nopeg" name="nopeg" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('nopeg', $user->nopeg)" autocomplete="nopeg" />
                    <x-input-error class="mt-2" :messages="$errors->get('nopeg')" />
                </div>

                <!-- Address -->
                <div>
                    <x-input-label for="address" :value="__('Address')" class="font-semibold" />
                    <x-text-input id="address" name="address" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('address', $user->address)" autocomplete="address" />
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>

                <!-- Phone -->
                <div>
                    <x-input-label for="no_wa" :value="__('WhatsApp Number')" class="font-semibold" />
                    <x-text-input id="no_wa" name="no_wa" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('no_wa', $user->no_wa)" placeholder="62812345678" autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('no_wa')" />
                </div>

                <!-- Join Date -->
                <div>
                    <x-input-label for="tgl_masuk" :value="__('Join Date')" class="font-semibold" />
                    <x-text-input id="tgl_masuk" name="tgl_masuk" type="date" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('tgl_masuk', $user->tgl_masuk ? \Carbon\Carbon::parse($user->tgl_masuk)->format('Y-m-d') : '')" autocomplete="bday" />
                    <x-input-error class="mt-2" :messages="$errors->get('tgl_masuk')" />
                </div>
            </div>
        </div>

        {{-- POSITION & CONTRACT --}}
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                {{ __('Position & Contract') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jabatan -->
                <div>
                    <x-input-label for="jabatan" :value="__('Position')" class="font-semibold" />
                    <select id="jabatan" name="jabatan" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('jabatan') border-red-500 @enderror">
                        <option value="">-- {{ __('Select Position') }} --</option>
                        <option value="Web Developer" {{ old('jabatan', $user->jabatan) == 'Web Developer' ? 'selected' : '' }}>Web Developer</option>
                        <option value="Mobile Developer" {{ old('jabatan', $user->jabatan) == 'Mobile Developer' ? 'selected' : '' }}>Mobile Developer</option>
                        <option value="System Analyst" {{ old('jabatan', $user->jabatan) == 'System Analyst' ? 'selected' : '' }}>System Analyst</option>
                        <option value="UI UX Designer" {{ old('jabatan', $user->jabatan) == 'UI UX Designer' ? 'selected' : '' }}>UI UX Designer</option>
                        <option value="Technical Writer" {{ old('jabatan', $user->jabatan) == 'Technical Writer' ? 'selected' : '' }}>Technical Writer</option>
                        <option value="IoT Developer" {{ old('jabatan', $user->jabatan) == 'IoT Developer' ? 'selected' : '' }}>IoT Developer</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('jabatan')" />
                </div>

                <!-- Kontrak (read-only info) -->
                <div>
                    <x-input-label for="kontrak" :value="__('Contract Type')" class="font-semibold" />
                    <select id="kontrak" name="kontrak" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kontrak') border-red-500 @enderror">
                        <option value="">-- {{ __('Select Contract') }} --</option>
                        <option value="Join Development PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'Join Development PT Bukit Asam' ? 'selected' : '' }}>Join Development PT Bukit Asam</option>
                        <option value="K3L PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'K3L PT Bukit Asam' ? 'selected' : '' }}>K3L PT Bukit Asam</option>
                        <option value="Supply Chain PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'Supply Chain PT Bukit Asam' ? 'selected' : '' }}>Supply Chain PT Bukit Asam</option>
                        <option value="SDMO PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'SDMO PT Bukit Asam' ? 'selected' : '' }}>SDMO PT Bukit Asam</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('kontrak')" />
                </div>
            </div>
        </div>

        {{-- PERSONAL INFORMATION --}}
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z"/></svg>
                {{ __('Personal Information') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Motor -->
                <div>
                    <x-input-label for="motor" :value="__('Vehicle Plate Number')" class="font-semibold" />
                    <x-text-input id="motor" name="motor" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('motor', $user->motor)" placeholder="Example: B 1234 XYZ" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('motor')" />
                </div>

                <!-- Ukuran Baju -->
                <div>
                    <x-input-label for="ukuran_baju" :value="__('Shirt Size')" class="font-semibold" />
                    <select id="ukuran_baju" name="ukuran_baju" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('ukuran_baju') border-red-500 @enderror">
                        <option value="">-- {{ __('Select Size') }} --</option>
                        <option value="XS" {{ old('ukuran_baju', $user->ukuran_baju) == 'XS' ? 'selected' : '' }}>XS</option>
                        <option value="S" {{ old('ukuran_baju', $user->ukuran_baju) == 'S' ? 'selected' : '' }}>S</option>
                        <option value="M" {{ old('ukuran_baju', $user->ukuran_baju) == 'M' ? 'selected' : '' }}>M</option>
                        <option value="L" {{ old('ukuran_baju', $user->ukuran_baju) == 'L' ? 'selected' : '' }}>L</option>
                        <option value="XL" {{ old('ukuran_baju', $user->ukuran_baju) == 'XL' ? 'selected' : '' }}>XL</option>
                        <option value="XXL" {{ old('ukuran_baju', $user->ukuran_baju) == 'XXL' ? 'selected' : '' }}>XXL</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('ukuran_baju')" />
                </div>
            </div>
        </div>

        {{-- BANKING INFORMATION --}}
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                {{ __('Banking Information') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bank -->
                <div>
                    <x-input-label for="bank" :value="__('Bank Name')" class="font-semibold" />
                    <x-text-input id="bank" name="bank" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('bank', $user->bank)" placeholder="BRI, BCA, Mandiri, etc" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('bank')" />
                </div>

                <!-- No Rekening -->
                <div>
                    <x-input-label for="norek" :value="__('Account Number')" class="font-semibold" />
                    <x-text-input id="norek" name="norek" type="text" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('norek', $user->norek)" placeholder="1234567890" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('norek')" />
                </div>
            </div>
        </div>

        {{-- SALARY & ALLOWANCE INFORMATION --}}
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8.5 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm6 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM9 6.5a3 3 0 11-6 0 3 3 0 016 0zm6 0a3 3 0 11-6 0 3 3 0 016 0zM12.5 16a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                {{ __('Salary & Allowance') }}
            </h3>

            <!-- Kontrak History -->
            @if ($user->detailKontrakUsers->count() > 0)
            <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ __('Contract History') }}
                </h4>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($user->detailKontrakUsers->sortByDesc('tgl_mulai_kontrak') as $detail)
                    <div class="flex items-center justify-between p-3 bg-white rounded border border-blue-100 hover:shadow-md transition">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $detail->kontrak ?? '-' }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $detail->tgl_mulai_kontrak ? $detail->tgl_mulai_kontrak->format('d M Y') : '-' }} 
                                @if ($detail->tgl_selesai_kontrak)
                                    s/d {{ $detail->tgl_selesai_kontrak->format('d M Y') }}
                                @else
                                    - {{ __('Ongoing') }}
                                @endif
                                <!-- masa kontrak dalam tahun dan bulan -->
                                ({{ $detail->tgl_mulai_kontrak ? $detail->tgl_selesai_kontrak->locale('id')->diffForHumans($detail->tgl_mulai_kontrak ?? now(), ['parts' => 3, 'short' => false, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) : '-' }})
                            </p>
                        </div>
                        <div class="text-right">
                            @if ($detail->is_active)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold mb-1">{{ __('Active') }}</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold mb-1">{{ __('Inactive') }}</span>
                            @endif
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($detail->total_gaji, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form Kontrak Aktif -->
            <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                {{ $detailKontrakAktif ? __('Update Active Contract') : __('Add New Contract') }}
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Tgl Mulai Kontrak -->
                <div>
                    <x-input-label for="tgl_mulai_kontrak" :value="__('Contract Start Date')" class="font-semibold" />
                    <x-text-input id="tgl_mulai_kontrak" name="tgl_mulai_kontrak" type="date" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('tgl_mulai_kontrak', $detailKontrakAktif?->tgl_mulai_kontrak?->format('Y-m-d'))" required />
                    <x-input-error class="mt-2" :messages="$errors->get('tgl_mulai_kontrak')" />
                </div>

                <!-- Tgl Selesai Kontrak -->
                <div>
                    <x-input-label for="tgl_selesai_kontrak" :value="__('Contract End Date (Optional)')" class="font-semibold" />
                    <x-text-input id="tgl_selesai_kontrak" name="tgl_selesai_kontrak" type="date" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('tgl_selesai_kontrak', $detailKontrakAktif?->tgl_selesai_kontrak?->format('Y-m-d'))" />
                    <x-input-error class="mt-2" :messages="$errors->get('tgl_selesai_kontrak')" />
                </div>

                <!-- Kontrak Type -->
                <div>
                    <x-input-label for="kontrak_new" :value="__('Contract Type')" class="font-semibold" />
                    <select id="kontrak_new" name="kontrak" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kontrak') border-red-500 @enderror">
                        <option value="">-- {{ __('Select Contract') }} --</option>
                        <option value="Join Development PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'Join Development PT Bukit Asam' ? 'selected' : '' }}>Join Development PT Bukit Asam</option>
                        <option value="K3L PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'K3L PT Bukit Asam' ? 'selected' : '' }}>K3L PT Bukit Asam</option>
                        <option value="Supply Chain PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'Supply Chain PT Bukit Asam' ? 'selected' : '' }}>Supply Chain PT Bukit Asam</option>
                        <option value="SDMO PT Bukit Asam" {{ old('kontrak', $detailKontrakAktif?->kontrak) == 'SDMO PT Bukit Asam' ? 'selected' : '' }}>SDMO PT Bukit Asam</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('kontrak')" />
                </div>
            </div>

            <!-- Salary Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <x-input-label for="gaji_pokok" :value="__('Base Salary')" class="font-semibold" />
                    <x-text-input id="gaji_pokok" name="gaji_pokok" type="number" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('gaji_pokok', $detailKontrakAktif?->gaji_pokok)" placeholder="5000000" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gaji_pokok')" />
                </div>

                <div>
                    <x-input-label for="gaji_tunjangan_tetap" :value="__('Fixed Allowance')" class="font-semibold" />
                    <x-text-input id="gaji_tunjangan_tetap" name="gaji_tunjangan_tetap" type="number" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('gaji_tunjangan_tetap', $detailKontrakAktif?->gaji_tunjangan_tetap)" placeholder="500000" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gaji_tunjangan_tetap')" />
                </div>

                <div>
                    <x-input-label for="gaji_tunjangan_makan" :value="__('Meal Allowance')" class="font-semibold" />
                    <x-text-input id="gaji_tunjangan_makan" name="gaji_tunjangan_makan" type="number" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('gaji_tunjangan_makan', $detailKontrakAktif?->gaji_tunjangan_makan)" placeholder="200000" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gaji_tunjangan_makan')" />
                </div>

                <div>
                    <x-input-label for="gaji_tunjangan_transport" :value="__('Transport + Housing Allowance')" class="font-semibold" />
                    <x-text-input id="gaji_tunjangan_transport" name="gaji_tunjangan_transport" type="number" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('gaji_tunjangan_transport', $detailKontrakAktif?->gaji_tunjangan_transport)" placeholder="1000000" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gaji_tunjangan_transport')" />
                </div>

                <div>
                    <x-input-label for="gaji_tunjangan_lain" :value="__('Other Allowance')" class="font-semibold" />
                    <x-text-input id="gaji_tunjangan_lain" name="gaji_tunjangan_lain" type="number" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('gaji_tunjangan_lain', $detailKontrakAktif?->gaji_tunjangan_lain)" placeholder="300000" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gaji_tunjangan_lain')" />
                </div>

                <div>
                    <x-input-label for="gaji_bpjs" :value="__('BPJS')" class="font-semibold" />
                    <x-text-input id="gaji_bpjs" name="gaji_bpjs" type="number" class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" :value="old('gaji_bpjs', $detailKontrakAktif?->gaji_bpjs)" placeholder="100000" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('gaji_bpjs')" />
                </div>
            </div>

            <!-- Total Summary -->
            @php
                $total = ($detailKontrakAktif?->gaji_pokok ?? 0) + 
                         ($detailKontrakAktif?->gaji_tunjangan_tetap ?? 0) + 
                         ($detailKontrakAktif?->gaji_tunjangan_makan ?? 0) + 
                         ($detailKontrakAktif?->gaji_tunjangan_transport ?? 0) + 
                         ($detailKontrakAktif?->gaji_tunjangan_lain ?? 0) + 
                         ($detailKontrakAktif?->gaji_bpjs ?? 0);
            @endphp
            <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 uppercase">{{ __('Total Salary & Allowance') }}</p>
                <p class="text-3xl font-bold text-orange-600 mt-2" id="totalSalary">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 text-green-800 rounded-lg"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ __('Profile updated successfully!') }}
                </div>
            @endif
        </div>
    </form>
</section>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-calculate total salary
document.addEventListener('DOMContentLoaded', function() {
    const salaryInputs = [
        'gaji_pokok',
        'gaji_tunjangan_tetap',
        'gaji_tunjangan_makan',
        'gaji_tunjangan_transport',
        'gaji_tunjangan_lain',
        'gaji_bpjs'
    ];

    function calculateTotal() {
        let total = 0;
        salaryInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                total += parseInt(input.value) || 0;
            }
        });

        const totalElement = document.getElementById('totalSalary');
        if (totalElement) {
            totalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }
    }

    salaryInputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', calculateTotal);
        }
    });
});
</script>
