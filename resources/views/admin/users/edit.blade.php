
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Edit User</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nopeg">No Pegawai</label>
                                    <input type="number" name="nopeg" id="nopeg" class="form-control @error('nopeg') is-invalid @enderror" value="{{ old('nopeg', $user->nopeg) }}">
                                    @error('nopeg')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kontrak">Kontrak</label>
                            <!-- select -->
                             
                            <select name="kontrak" id="kontrak" class="form-control @error('kontrak') is-invalid @enderror">
                                <option value="">Pilih Jenis Kontrak</option>
                                <option value="Join Development PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'Join Development PT Bukit Asam' ? 'selected' : '' }}>Join Development PT Bukit Asam</option>
                                <option value="K3L PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'K3L PT Bukit Asam' ? 'selected' : '' }}>K3L PT Bukit Asam</option>
                                <option value="Supply Chain PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'Supply Chain PT Bukit Asam' ? 'selected' : '' }}>Supply Chain PT Bukit Asam</option>
                                <option value="SDMO PT Bukit Asam" {{ old('kontrak', $user->kontrak) == 'SDMO PT Bukit Asam' ? 'selected' : '' }}>SDMO PT Bukit Asam</option>
                            </select>
                            
                            @error('kontrak')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <!-- select -->
                            <select name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                                <option value="">Pilih Jabatan</option>
                                <option value="Web Developer" {{ old('jabatan', $user->jabatan) == 'Web Developer' ? 'selected' : '' }}>Web Developer</option>
                                <option value="Mobile Developer" {{ old('jabatan', $user->jabatan) == 'Mobile Developer' ? 'selected' : '' }}>Mobile Developer</option>
                                <option value="System Analyst" {{ old('jabatan', $user->jabatan) == 'System Analyst' ? 'selected' : '' }}>System Analyst</option>
                                <option value="UI UX Designer" {{ old('jabatan', $user->jabatan) == 'UI UX Designer' ? 'selected' : '' }}>UI UX Designer</option>
                                <option value="Technical Writer" {{ old('jabatan', $user->jabatan) == 'Technical Writer' ? 'selected' : '' }}>Technical Writer</option>
                                <option value="IoT Developer" {{ old('jabatan', $user->jabatan) == 'IoT Developer' ? 'selected' : '' }}>IoT Developer</option>
                            </select>
                            @error('jabatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_wa">No WA</label>
                            <input type="text" name="no_wa" id="no_wa" class="form-control @error('no_wa') is-invalid @enderror" value="{{ old('no_wa', $user->no_wa) }}">
                            @error('no_wa')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="motor">Motor</label>
                            <input type="text" name="motor" id="motor" class="form-control @error('motor') is-invalid @enderror" value="{{ old('motor', $user->motor) }}">
                            @error('motor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ukuran_baju">Ukuran Baju</label>
                            <input type="text" name="ukuran_baju" id="ukuran_baju" class="form-control @error('ukuran_baju') is-invalid @enderror" value="{{ old('ukuran_baju', $user->ukuran_baju) }}">
                            @error('ukuran_baju')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_masuk">Tgl Masuk</label>
                            <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control @error('tgl_masuk') is-invalid @enderror" value="{{ old('tgl_masuk', $user->tgl_masuk ? \Carbon\Carbon::parse($user->tgl_masuk)->format('Y-m-d') : '') }}">
                            @error('tgl_masuk')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="attachment_ttd">Tanda Tangan</label>
                            <input type="file" name="attachment_ttd" id="attachment_ttd" class="form-control-file @error('attachment_ttd') is-invalid @enderror">
                            @if ($user->attachment_ttd)
                                <p class="mt-2 text-sm text-gray-600">Current file: <a href="{{ asset('storage/' . $user->attachment_ttd) }}" target="_blank" class="text-blue-500 hover:underline">View Tanda Tangan</a></p>
                            @endif
                            @error('attachment_ttd')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="roles">Roles</label>
                            <select name="roles[]" id="roles" class="form-control @error('roles') is-invalid @enderror" multiple required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ in_array($role->name, $user->roles->pluck('name')->toArray()) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                                @error('roles')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </select>
                        </div>
                        <x-primary-button>
                            {{ __('Save Changes') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#roles').select2();
        });
    </script>
    @endpush
</x-app-layout>