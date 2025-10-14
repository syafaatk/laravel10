<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
                </div>
            @endif
            </div>
            <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="no_wa" :value="__('No WA')" />
                    <x-text-input id="no_wa" name="no_wa" type="text" class="mt-1 block w-full" :value="old('no_wa', $user->no_wa)" autocomplete="no_wa" />
                    <x-input-error class="mt-2" :messages="$errors->get('no_wa')" />
                </div>
                <div>
                    <x-input-label for="nopeg" :value="__('No Pegawai')" />
                    <x-text-input id="nopeg" name="nopeg" type="text" class="mt-1 block w-full" :value="old('nopeg', $user->nopeg)" autocomplete="nopeg" />
                    <x-input-error class="mt-2" :messages="$errors->get('nopeg')" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="kontrak" :value="__('Kontrak')" />
                    <!-- select -->

                    <select id="kontrak" name="kontrak" class="form-select mt-1 block w-full @error('kontrak') border-red-500 @enderror" >
                        <option value="">-- Select Kontrak --</option>
                        <option value="Join Development PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'Join Development PT Bukit Asam' ? 'selected' : '' }}>Join Development PT Bukit Asam</option>
                        <option value="K3L PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'K3L PT Bukit Asam' ? 'selected' : '' }}>K3L PT Bukit Asam</option>
                        <option value="Supply Chain PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'Supply Chain PT Bukit Asam' ? 'selected' : '' }}>Supply Chain PT Bukit Asam</option>
                        <option value="SDMO PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'SDMO PT Bukit Asam' ? 'selected' : '' }}>SDMO PT Bukit Asam</option>

                    </select>
                    
                    <x-input-error class="mt-2" :messages="$errors->get('kontrak')" />
                </div>
                <div>
                    <x-input-label for="jabatan" :value="__('Jabatan')" />
                    <!-- select -->
                    <select id="jabatan" name="jabatan" class="form-select mt-1 block w-full @error('jabatan') border-red-500 @enderror" >
                        <option value="">Pilih Jabatan</option>
                        <option value="Web Developer" {{ old('jabatan', $user->jabatan) == 'Web Developer' ? 'selected' : '' }}>Web Developer</option>
                        <option value="Mobile Developer" {{ old('jabatan', $user->jabatan) == 'Mobile Developer' ? 'selected' : '' }}>Mobile Developer</option>
                        <option value="System Analyst" {{ old('jabatan', $user->jabatan) == 'System Analyst' ? 'selected' : '' }}>System Analyst</option>
                        <option value="UI UX Designer" {{ old('jabatan', $user->jabatan) == 'UI UX Designer' ? 'selected' : '' }}>UI UX Designer</option>
                        <option value="Technical Writer" {{ old('jabatan', $user->jabatan) == 'Technical Writer' ? 'selected' : '' }}>Technical Writer</option>
                        <option value="IoT Developer" {{ old('jabatan', $user->jabatan) == 'IoT Developer' ? 'selected' : '' }}>IoT Developer</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('jabatan')" />
                </div>
            </div>

            <div>
            <x-input-label for="motor" :value="__('Motor')" />
            <x-text-input id="motor" name="motor" type="text" class="mt-1 block w-full" :value="old('motor', $user->motor)" autocomplete="motor" />
            <x-input-error class="mt-2" :messages="$errors->get('motor')" />
            </div>
            <div>
                <x-input-label for="ukuran_baju" :value="__('Ukuran Baju')" />
                <x-text-input id="ukuran_baju" name="ukuran_baju" type="text" class="mt-1 block w-full" :value="old('ukuran_baju', $user->ukuran_baju)" autocomplete="ukuran_baju" />
                <x-input-error class="mt-2" :messages="$errors->get('ukuran_baju')" />
            </div>
            <div>
                <x-input-label for="tgl_masuk" :value="__('Tgl Masuk')" />
                <x-text-input id="tgl_masuk" name="tgl_masuk" type="date" class="mt-1 block w-full" :value="old('tgl_masuk', $user->tgl_masuk ? \Carbon\Carbon::parse($user->tgl_masuk)->format('Y-m-d') : '')" autocomplete="tgl_masuk" />
                <x-input-error class="mt-2" :messages="$errors->get('tgl_masuk')" />
            </div>
            <div>
            <x-input-label for="attachment_ttd" :value="__('Tanda Tangan')" />
            <input id="attachment_ttd" name="attachment_ttd" type="file" class="mt-1 block w-full" />
            @if ($user->attachment_ttd)
                <p class="mt-2 text-sm text-gray-600">Current file: <a href="{{ asset('storage/' . $user->attachment_ttd) }}" target="_blank" class="text-blue-500 hover:underline">View Tanda Tangan</a></p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('attachment_ttd')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
