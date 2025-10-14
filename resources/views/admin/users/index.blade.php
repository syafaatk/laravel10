<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>User List</span>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No Pegawai</th>
                                <th>Name</th>
                                <th>Kontrak</th>
                                <th>Jabatan</th>
                                <th>Ukuran Baju</th>
                                <th>Motor</th>
                                <th>Tgl Masuk</th>
                                <th>Lama Kerja</th>
                                <th>Sisa Cuti</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->nopeg }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->kontrak }}</td>
                                    <td>{{ $user->jabatan }}</td>
                                    <td>{{ $user->ukuran_baju }}</td>
                                    <td>{{ $user->motor ? $user->motor : 'No' }}</td>
                                    <td>{{ $user->tgl_masuk ? \Carbon\Carbon::parse($user->tgl_masuk)->format('d M Y') : '' }}</td>
                                    <td>
                                        @if($user->tgl_masuk)
                                            {{ \Carbon\Carbon::parse($user->tgl_masuk)->diffInYears(\Carbon\Carbon::now()) }} years
                                        @endif
                                    </td>
                                    <td>{{ 12 - $user->cuti_approved_sum_days_requested ?? 0 }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge badge-success">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" style="background-color: red; color: white;" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable();
        });
    </script>
    @endpush
</x-app-layout>
