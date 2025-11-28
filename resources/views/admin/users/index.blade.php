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
                        <span class="h5 m-0">User List</span>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Showing all users. Click "Sisa" to view cuti detail.</small>
                        </div>
                        <div>
                            <button id="refresh-users" class="btn btn-sm btn-outline-secondary">Refresh</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="users-table" class="table table-bordered table-striped table-hover">
                            <thead class="table-light">
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
                                    @php
                                        $nopeg = $user->nopeg ?? '-';
                                        $tglMasuk = $user->tgl_masuk ? \Carbon\Carbon::parse($user->tgl_masuk) : null;
                                        $years = $tglMasuk ? $tglMasuk->diffInYears(\Carbon\Carbon::now()) : null;
                                        $month = $tglMasuk ? $tglMasuk->addYears($years)->diffInMonths(\Carbon\Carbon::now()) : null;

                                        $gajiParts = [
                                            $user->gaji_tunjangan_tetap ?? 0,
                                            $user->gaji_tunjangan_makan ?? 0,
                                            $user->gaji_tunjangan_transport ?? 0,
                                            $user->gaji_tunjangan_lain ?? 0,
                                            $user->gaji_pokok ?? 0,
                                            $user->gaji_bpjs ?? 0,
                                        ];
                                        $totalGaji = array_sum($gajiParts);

                                        // safe sisa cuti calculation (default annual quota 12)
                                        $usedDays = $user->cuti_approved_sum_days_requested ?? 0;
                                        $annualQuota = $user->annual_cuti_quota ?? 12;
                                        $remaining = max(0, $annualQuota - $usedDays);
                                    @endphp
                                    <tr>
                                        <td class="align-middle">{{ $nopeg }}</td>
                                        <td class="align-middle">{{ $user->name }}</td>
                                        <td class="align-middle">{{ $user->kontrak ?? '-' }}</td>
                                        <td class="align-middle">{{ $user->jabatan ?? '-' }}</td>
                                        <td class="align-middle">{{ $user->ukuran_baju ?? '-' }}</td>
                                        <td class="align-middle">{{ $user->motor ? 'Yes' : 'No' }}</td>
                                        <td class="align-middle">{{ $tglMasuk ? $tglMasuk->format('d M Y') : '-' }}</td>
                                        <td class="align-middle">{{ $years !== null ? $years . ' tahun' : '-' }}, {{ $month !== null ? $month . ' bulan' : '-' }}</td>
                                        <td class="align-middle">Rp {{ number_format($totalGaji, 0, ',', '.') }}</td>
                                        <td class="align-middle">
                                            <button type="button"
                                                class="btn btn-sm view-cuti-detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailCutiModal"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                title="Klik untuk melihat detail cuti">
                                                <span class="badge {{ $remaining > 5 ? 'bg-success' : ($remaining > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                    Sisa - {{ $remaining }} hari
                                                </span>
                                            </button>
                                            <div class="text-muted small mt-1">Dipakai: {{ $usedDays }} / {{ $annualQuota }}</div>
                                        </td>

                                        <td class="align-middle">
                                            @if($user->roles && $user->roles->count())
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-info text-dark">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">No roles</span>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger bg-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- modal detail cuti-->
                    <div class="modal fade" id="detailCutiModal" tabindex="-1" aria-labelledby="detailCutiModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailCutiModalLabel">Detail Cuti Karyawan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div id="cuti-loading" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                                        <div class="mt-2 text-muted">Memuat data cuti...</div>
                                    </div>

                                    <div id="cuti-content" class="d-none">
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

                                        <div id="cuti-summary" class="mt-3">
                                            <!-- optional summary injected by server -->
                                        </div>
                                    </div>

                                    <div id="cuti-error" class="d-none text-danger text-center py-3">
                                        Error loading cuti details. Please try again.
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end modal -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable with responsive and sensible defaults
            $('#users-table').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                columnDefs: [
                    { orderable: false, targets: [9, 10, 11] } // sisa cuti, roles, actions not orderable
                ]
            });

            // refresh button simple action
            $('#refresh-users').on('click', function() {
                location.reload();
            });

            // Handle click on "Sisa" (View Cuti Detail) button
            $('.view-cuti-detail').on('click', function() {
                var userId = $(this).data('user-id');
                var userName = $(this).data('user-name') || 'User';
                var cutiDetailRows = $('#cuti-detail-rows');

                // show loading
                $('#detailCutiModalLabel').text('Detail Cuti: ' + userName);
                $('#cuti-loading').removeClass('d-none');
                $('#cuti-content').addClass('d-none');
                $('#cuti-error').addClass('d-none');
                cutiDetailRows.html('');

                $.ajax({
                    url: '{{ route('admin.ajax-cuti-details') }}',
                    type: 'GET',
                    data: { user_id: userId },
                    success: function(response) {
                        $('#cuti-loading').addClass('d-none');

                        if (!response || response.trim() === '') {
                            $('#cuti-error').removeClass('d-none').text('No cuti details found for this user.');
                            return;
                        }

                        // assume server returns HTML rows and optional summary container
                        $('#cuti-detail-rows').html(response);
                        $('#cuti-content').removeClass('d-none');
                    },
                    error: function(xhr) {
                        console.error("Error loading cuti details:", xhr.responseText);
                        $('#cuti-loading').addClass('d-none');
                        $('#cuti-error').removeClass('d-none').text('Error loading cuti details. Please try again.');
                    }
                });
            });

            // clear modal content when hidden
            $('#detailCutiModal').on('hidden.bs.modal', function () {
                $('#cuti-detail-rows').empty();
                $('#cuti-content').addClass('d-none');
                $('#cuti-loading').removeClass('d-none');
                $('#cuti-error').addClass('d-none');
                $('#detailCutiModalLabel').text('Detail Cuti Karyawan');
            });
        });
    </script>
    @endpush
</x-app-layout>