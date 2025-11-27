<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reimbursements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>My Reimbursement Requests</span>
                        <a href="{{ route('reimbursements.create') }}" class="btn btn-primary">New Request</a>
                    </div>
                </div>
                <div class="card-body">
                    
                    {{-- START: DEDICATED PRINT FORM (MOVED OUTSIDE THE TABLE) --}}
                    {{-- This form will be submitted using JavaScript to pass selected IDs --}}
                    <form id="print-form" action="{{ route('reimbursements.print') }}" method="POST" target="_blank" class="mb-3">
                        @csrf
                        {{-- Hidden field to store the IDs of selected checkboxes --}}
                        <input type="hidden" name="reimbursement_ids" id="reimbursement_ids_to_print">

                        @if(Auth::user()->hasRole('admin'))
                            <x-primary-button type="submit" id="print-button">
                                {{ __('Print Selected Reimbursements') }}
                            </x-primary-button>
                        @endif
                    </form>
                    {{-- END: DEDICATED PRINT FORM --}}

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="reimbursements-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Title</th>
                            <th>Type</th>
                            @if (Auth::user()->hasRole('admin'))
                                <th>Requested By</th>
                            @endif
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Laporan</th>
                            <th>Date</th>
                            <th>Date Approval</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($reimbursements as $reimbursement)
                                <tr>
                                    {{-- Checkboxes are now just inputs, not tied to the print form submission --}}
                                    <td><input type="checkbox" value="{{ $reimbursement->id }}" class="reimbursement-checkbox"></td>
                                    <td>{{ $reimbursement->title }}</td>
                                    <td>
                                        @if ($reimbursement->tipe == 1) {{ __('Transportasi') }} @elseif ($reimbursement->tipe == 2) {{ __('Makan-makan') }} @else {{ __('Lain-lain') }} @endif
                                    </td>
                                    @if (Auth::user()->hasRole('admin'))
                                        <td>{{ $reimbursement->user->name }}</td>
                                    @endif
                                    <td>Rp {{ number_format($reimbursement->amount, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($reimbursement->status == 'pending')
                                            <span class="badge badge-warning">Pengajuan</span>
                                        @elseif ($reimbursement->status == 'approved')
                                            <span class="badge badge-success">Diajukan ke QT </span>
                                        @elseif ($reimbursement->status == 'done')
                                            <span class="badge badge-primary">Done</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reimbursement->laporanReimbursement)
                                            {{ $reimbursement->laporanReimbursement->title }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $reimbursement->created_at->format('d M Y') }}</td>
                                    <td>{{ $reimbursement->processed_at?->format('d M Y') ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('reimbursements.show', $reimbursement) }}" class="btn btn-sm btn-info">View</a>

                                        @if (Auth::user()->hasRole('admin') && $reimbursement->status == 'pending')
                                            <!-- Open modal to select laporan/reimbursement report -->
                                            <button type="button" class="btn btn-sm btn-success bg-success" onclick="openApproveModal({{ $reimbursement->id }}, '{{ addslashes($reimbursement->title) }}')">
                                                Diajukan ke QT
                                            </button>
                                            <form id="delete-form-{{ $reimbursement->id }}" action="{{ route('reimbursements.destroy', $reimbursement) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger bg-danger" onclick="return confirm('Are you sure you want to delete this reimbursement request?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal (place near bottom of file, once) -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="approveForm" method="POST" action="">
          @csrf
          @method('PATCH')
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="approveModalLabel">Ajukan ke QT</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="reimbursement_id" id="approve_reimbursement_id">

              <div class="mb-3">
                <label for="laporan_id" class="form-label">Pilih Laporan Reimbursement</label>
                <select id="laporan_id" name="laporan_id" class="form-select">
                  <option value="">-- Pilih laporan (atau buat baru) --</option>
                  @foreach($laporanReimbursements ?? [] as $lap)
                    <option value="{{ $lap->id }}">{{ $lap->title }} ({{ $lap->start_date->format('d M Y') }} - {{ $lap->end_date->format('d M Y') }})</option>
                  @endforeach
                  <option value="__new__">Buat Laporan Baru...</option>
                </select>
              </div>

              <div id="newLaporanContainer" class="d-none">
                <div class="mb-3">
                  <label for="new_laporan_title" class="form-label">Title Laporan</label>
                  <input type="text" id="new_laporan_title" name="new_laporan_title" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="new_laporan_start" class="form-label">Start Date</label>
                  <input type="date" id="new_laporan_start" name="new_laporan_start" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="new_laporan_end" class="form-label">End Date</label>
                  <input type="date" id="new_laporan_end" name="new_laporan_end" class="form-control">
                </div>
              </div>

              <div class="form-text">Jika anda memilih "Buat Laporan Baru", isikan data laporan. Jika memilih laporan existing, reimbursement akan terkait ke laporan tersebut.</div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary btn-primary">Ajukan</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    @push('scripts')
    {{-- Make sure you've included jQuery and DataTables before this script --}}
    <script>
        $(document).ready(function() {
            var table = $('#reimbursements-table').DataTable({
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                }],
                'order': [
                    [1, 'asc']
                ]
            });

            // Handle Select All logic for the current page/search results
            $('#select-all').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"].reimbursement-checkbox', rows).prop('checked', this.checked);
            });

            // Handle Select All state when individual checkboxes change
            $('#reimbursements-table tbody').on('change', 'input[type="checkbox"].reimbursement-checkbox', function() {
                if (!this.checked) {
                    var el = $('#select-all').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });

            // FIX: JavaScript to handle the Print button submission
            $('#print-button').on('click', function(e) {
                e.preventDefault(); // Stop the default form submission

                var selectedIds = [];
                // Collect IDs of all checked checkboxes
                $('.reimbursement-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert('Please select at least one reimbursement to print.');
                    return;
                }

                // Put the collected IDs into the hidden input field
                $('#reimbursement_ids_to_print').val(selectedIds.join(','));

                // Manually submit the print form
                $('#print-form').submit();
            });
        });

        function openApproveModal(reimbursementId, reimbursementTitle) {
            // set form action (use route helper pattern)
            const form = document.getElementById('approveForm');
            // Set action to route with placeholder id (update below)
            form.action = '{{ url("reimbursements") }}/' + reimbursementId + '/approve';
            document.getElementById('approve_reimbursement_id').value = reimbursementId;
            document.getElementById('laporan_id').value = '';
            document.getElementById('newLaporanContainer').classList.add('d-none');
            // show bootstrap modal
            var approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
            approveModal.show();
        }

        document.getElementById('laporan_id').addEventListener('change', function() {
            var val = this.value;
            var container = document.getElementById('newLaporanContainer');
            if (val === '__new__') container.classList.remove('d-none'); else container.classList.add('d-none');
        });
    </script>
    @endpush
</x-app-layout>