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
                                <th>Total Gaji</th>
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
                                    <td>@php
                                            $totalGaji = $user->gaji_tunjangan_tetap + $user->gaji_tunjangan_makan + $user->gaji_tunjangan_transport + $user->gaji_tunjangan_lain + $user->gaji_pokok + $user->gaji_bpjs;
                                        @endphp
                                        {{ number_format($totalGaji, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary view-cuti-detail" style="background-color: green; color: white;" data-bs-toggle="modal" data-bs-target="#detailCutiModal" data-user-id="{{ $user->id }}">
                                            Sisa - {{ 12 - $user->cuti_approved_sum_days_requested ?? 0 }} days
                                        </button>
                                    </td>
                                    
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
                <!-- modal detail cuti-->
                 
                <div class="modal fade" id="detailCutiModal" tabindex="-1" aria-labelledby="detailCutiModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailCutiModalLabel">Detail Cuti Karyawan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">                                
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Jenis Cuti</th>
                                            <th>Mulai Cuti</th>
                                            <th>Akhir Cuti</th>
                                            <th>Jumlah Hari</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuti-detail-rows">
                                        <!-- Cuti details will be loaded here via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                </div>
            </div>
        </div>
    </div>
    

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#users-table').DataTable();
            
            // Handle click on "Lihat" (View Cuti Detail) button
            $('.view-cuti-detail').on('click', function() {
                var userId = $(this).data('user-id');

                var cutiDetailRows = $('#cuti-detail-rows');

                // 1. Clear previous content and show a loading message
                cutiDetailRows.html('<tr><td colspan="5" class="text-center">Loading cuti details...</td></tr>');
                
                // Optional: Update modal title with user ID/Name if available on button
                // var userName = $(this).closest('tr').find('td:eq(1)').text(); // Assuming Name is the 2nd column
                // $('#detailCutiModalLabel').text('Detail Cuti Karyawan: ' + userName);

                // 2. Perform AJAX request
                $.ajax({
                    url: '{{ route('admin.ajax-cuti-details') }}',
                    type: 'GET',
                    data: { 
                        user_id: userId
                    },
                    success: function(response) {
                        // 3. Insert the loaded HTML response
                        if (response.trim() === '') {
                            cutiDetailRows.html('<tr><td colspan="5" class="text-center">No cuti details found for this user.</td></tr>');
                        } else {
                            cutiDetailRows.html(response);
                        }
                    },
                    error: function(xhr) {
                        // 4. Handle error
                        console.error("Error loading cuti details:", xhr.responseText);
                        cutiDetailRows.html('<tr><td colspan="5" class="text-center text-danger">Error loading cuti details. Please try again.</td></tr>');
                    }
                });
            });
            
            // Ensure the modal is cleared when closed (optional but good practice)
            $('#detailCutiModal').on('hidden.bs.modal', function () {
                $('#cuti-detail-rows').empty();
                // Reset modal title if you changed it
                // $('#detailCutiModalLabel').text('Detail Cuti Karyawan');
            });
        });
    </script>
    @endpush
</x-app-layout>

