<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kontrak') }}
        </h2>
    </x-slot>

    <div class="row">
        <!-- ================================ -->
        <!-- PANEL KONTRAK & DETAIL KONTRAK  -->
        <!-- ================================ -->
        <div class="col-md-6 mb-4">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="panel-title">Kontrak</span>
                </div>
                <div class="card-body">
                    <!-- Form Tambah Kontrak -->
                    <form id="formKontrak" class="row g-2 mb-3">
                        <!-- TODO: sesuaikan dengan kolom di tabel Kontrak -->
                        <div class="col-md-6">
                            <label class="small-label">Nama Kontrak</label>
                            <input type="text" name="title_kontrak" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai_kontrak" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai_kontrak" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="small-label">Is Active</label>
                            <input type="number" name="is_active" class="form-control">
                        </div>
                        <div class="col-md-12 text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary bg-blue-600 hover:bg-blue-700">
                                Simpan Kontrak
                            </button>
                        </div>
                    </form>

                    <!-- Tabel Kontrak -->
                    <table class="table table-sm table-striped" id="tableKontrak">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Kontrak -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="panel-title">
                        Detail Kontrak
                    </span>
                    <span class="badge bg-info text-dark" id="badgeKontrakTerpilih">
                        Belum ada kontrak dipilih
                    </span>
                </div>
                <div class="card-body">
                    <!-- Form Tambah Detail Kontrak -->
                    <form id="formKontrakDetail" class="row g-2 mb-3">
                        <!-- TODO: sesuaikan dengan kolom di tabel KontrakDetail -->
                        <div class="col-md-6">
                            <label class="small-label">Title SPPH</label>
                            <input type="text" name="title_spph" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Tanggal Mulai SPPH</label>
                            <input type="date" name="tgl_mulai_spph" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Tanggal Selesai SPPH</label>
                            <input type="date" name="tgl_selesai_spph" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Is Active</label>
                            <input type="number" name="is_active" class="form-control">
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-secondary bg-gray-600 hover:bg-gray-700" id="btnSaveDetailKontrak" disabled>
                                Simpan Detail Kontrak
                            </button>
                        </div>
                    </form>

                    <!-- Tabel Detail Kontrak -->
                    <table class="table table-sm table-bordered" id="tableKontrakDetail">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title SPPH</th>
                            <th>Tanggal Mulai SPPH</th>
                            <th>Tanggal Selesai SPPH</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kontrak laporan -->
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="panel-title">
                        Kontrak Laporan dari Detail Kontrak Terpilih
                    </span>
                    <span class="badge bg-info text-dark" id="badgeKontrakLaporanTerpilih">
                        Belum ada detail kontrak dipilih
                    </span>
                </div>
                <div class="card-body">
                    <!-- Tabel Kontrak Laporan -->
                    <table class="table table-sm table-bordered" id="tableKontrakLaporan">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bulan Laporan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- diisi via JS -->
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>

        <!-- ================================ -->
        <!-- PANEL LAPORAN & DETAIL LAPORAN  -->
        <!-- ================================ -->
        <div class="col-md-6 mb-4">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="panel-title">Laporan</span>
                </div>
                <div class="card-body">
                    <!-- Form Tambah Laporan -->
                    <form id="formLaporan" class="row g-2 mb-3">
                        <!-- TODO: sesuaikan dengan kolom di tabel Laporan -->
                        <div class="col-md-6">
                            <label class="small-label">Nama Pegawai</label>
                            <input type="text" name="nama_pegawai" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Attachment file daily</label>
                            <input type="file" name="attachment_file_daily" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="small-label">Is Lock</label>
                            <input type="text" name="is_lock" class="form-control">
                        </div>
                        <div class="col-md-12 text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary bg-blue-600 hover:bg-blue-700">
                                Simpan Laporan
                            </button>
                        </div>
                    </form>

                    <!-- Tabel Laporan -->
                    <table class="table table-sm table-striped" id="tableLaporan">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bulan Laporan</th>
                            <th>Nama Pegawai</th>
                            <th>Attachment file daily</th>
                            <th>Is Lock</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Laporan -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="panel-title">Detail Laporan</span>
                    <span class="badge bg-info text-dark" id="badgeLaporanTerpilih">
                        Belum ada laporan dipilih
                    </span>
                </div>
                <div class="card-body">
                    <!-- Form Tambah Detail Laporan -->
                    <form id="formLaporanDetail" class="row g-2 mb-3" enctype="multipart/form-data">
                        <!-- TODO: sesuaikan dengan kolom di tabel LaporanDetail -->
                        <div class="col-md-8">
                            <label class="small-label">Nama Modul</label>
                            <input type="text" name="judul_modul" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="small-label">Judul Pekerjaan</label>
                            <input type="text" name="judul_pekerjaan" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small-label">Progress Pekerjaan</label>
                            <input type="number" name="progress_pekerjaan" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="small-label">Attachment Screenshot</label>
                            <input type="file" name="attachment_screenshots[]" class="form-control" multiple>
                        </div>
                        <div class="col-md-4">
                            <label class="small-label">Is Lock</label>
                            <input type="number" name="is_lock" class="form-control">
                        </div>
                        <div class="col-md-12 text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-secondary bg-gray-600 hover:bg-gray-700" id="btnSaveDetailLaporan" disabled>
                                Simpan Detail Laporan
                            </button>
                        </div>
                    </form>

                    <!-- Tabel Detail Laporan -->
                    <table class="table table-sm table-bordered" id="tableLaporanDetail">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Modul</th>
                            <th>Judul Pekerjaan</th>
                            <th>Progress Pekerjaan</th>
                            <th>Attachment Screenshot</th>
                            <th>Is Lock</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle JS (termasuk Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @push('scripts')
    <script>
        // ===============================
        // Konfigurasi dasar
        // ===============================
        const API_BASE = '/api'; // ganti jika prefix-mu berbeda

        // Selected ids untuk flow berjenjang
        let selectedKontrakId = null;
        let selectedKontrakDetailId = null;
        let selectedKontrakLaporanId = null;
        let selectedLaporanId = null;

        // ===============================
        // Helper fetch JSON
        // ===============================
        async function apiGet(url) {
            const res = await fetch(url);
            return await res.json();
        }

        async function apiPost(url, data) {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            return await res.json();
        }

        async function apiPostForm(url, formElement) {
            const formData = new FormData(formElement); // otomatis ambil file
            const res = await fetch(url, {
                method: 'POST',
                body: formData, // JANGAN set Content-Type manual!
            });
            return await res.json();
        }

        async function apiDelete(url) {
            const res = await fetch(url, { method: 'DELETE' });
            return res;
        }

        // ===============================
        // KONTRAK
        // ===============================
        async function loadKontrak() {
            const data = await apiGet(`${API_BASE}/kontraks`);
            const tbody = document.querySelector('#tableKontrak tbody');
            tbody.innerHTML = '';

            data.forEach(k => {
                const tr = document.createElement('tr');
                tr.classList.add('pointer');
                tr.dataset.id = k.id;

                tr.innerHTML = `
                    <td>${k.id}</td>
                    <td>${k.title_kontrak ?? '-'}</td>
                    <td>${(k.tgl_mulai_kontrak ?? '')} s/d ${(k.tgl_selesai_kontrak ?? 'Sekarang')}</td>
                    <td>${k.is_active ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete-kontrak" data-id="${k.id}">Hapus</button>
                    </td>
                `;

                tr.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-delete-kontrak')) return;
                    selectKontrakRow(k.id, tr);
                });

                tbody.appendChild(tr);
            });

            // event tombol hapus
            document.querySelectorAll('.btn-delete-kontrak').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const id = btn.dataset.id;
                    if (confirm('Yakin hapus kontrak ID ' + id + ' ?')) {
                        await apiDelete(`${API_BASE}/kontraks/${id}`);
                        if (selectedKontrakId === parseInt(id)) {
                            selectedKontrakId = null;
                            selectedKontrakDetailId = null;
                            selectedKontrakLaporanId = null;
                            selectedLaporanId = null;

                            document.getElementById('badgeKontrakTerpilih').innerText = 'Belum ada kontrak dipilih';
                            document.getElementById('badgeKontrakLaporanTerpilih').innerText = 'Belum ada detail kontrak dipilih';
                            document.getElementById('badgeLaporanTerpilih').innerText = 'Belum ada laporan dipilih';

                            document.querySelector('#tableKontrakDetail tbody').innerHTML = '';
                            document.querySelector('#tableKontrakLaporan tbody').innerHTML = '';
                            document.querySelector('#tableLaporanDetail tbody').innerHTML = '';
                            document.getElementById('btnSaveDetailKontrak').disabled = true;
                            document.getElementById('btnSaveDetailLaporan').disabled = true;
                        }
                        loadKontrak();
                    }
                });
            });
        }

        function selectKontrakRow(id, rowElement) {
            selectedKontrakId = id;
            selectedKontrakDetailId = null;
            selectedKontrakLaporanId = null;
            selectedLaporanId = null;

            // highlight
            document.querySelectorAll('#tableKontrak tbody tr').forEach(tr => tr.classList.remove('selected-row'));
            rowElement.classList.add('selected-row');

            document.getElementById('badgeKontrakTerpilih').innerText = 'Kontrak ID: ' + id;
            document.getElementById('btnSaveDetailKontrak').disabled = false;

            // reset downstream
            document.getElementById('badgeKontrakLaporanTerpilih').innerText = 'Belum ada detail kontrak dipilih';
            document.getElementById('badgeLaporanTerpilih').innerText = 'Belum ada laporan dipilih';
            document.querySelector('#tableKontrakLaporan tbody').innerHTML = '';
            document.querySelector('#tableLaporanDetail tbody').innerHTML = '';
            document.getElementById('btnSaveDetailLaporan').disabled = true;

            loadKontrakDetail(id);
        }

        // ===============================
        // DETAIL KONTRAK
        // ===============================
        async function loadKontrakDetail(kontrakId) {
            const data = await apiGet(`${API_BASE}/kontraks/${kontrakId}/details`);
            const tbody = document.querySelector('#tableKontrakDetail tbody');
            tbody.innerHTML = '';

            data.forEach(d => {
                const tr = document.createElement('tr');
                tr.classList.add('pointer');
                tr.dataset.id = d.id;

                tr.innerHTML = `
                    <td>${d.id}</td>
                    <td>${d.title_spph ?? '-'}</td>
                    <td>${d.tgl_mulai_spph ?? '-'}</td>
                    <td>${d.tgl_selesai_spph ?? '-'}</td>
                    <td>${d.is_active ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete-kontrak-detail" data-id="${d.id}">Hapus</button>
                        <button class="btn btn-sm btn-primary btn-generate-kontrak-laporan" data-id="${d.id}">Generate Kontrak Laporan</button>
                    </td>
                `;

                tr.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-delete-kontrak-detail')) return;
                    selectKontrakDetailRow(d.id, tr);
                });

                tr.querySelector('.btn-generate-kontrak-laporan').addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const id = d.id;
                    if (confirm('Yakin generate kontrak laporan untuk detail kontrak ID ' + id + ' ?')) {
                        await apiPost(`${API_BASE}/kontraks/${id}/laporans/generate`, {});
                        loadKontrakLaporan(id);
                    }
                });

                tbody.appendChild(tr);
            });

            document.querySelectorAll('.btn-delete-kontrak-detail').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const id = btn.dataset.id;
                    if (confirm('Yakin hapus detail kontrak ID ' + id + ' ?')) {
                        await apiDelete(`${API_BASE}/kontraks/${kontrakId}/details/${id}`);
                        loadKontrakDetail(kontrakId);
                    }
                });
            });
        }

        function selectKontrakDetailRow(id, rowElement) {
            selectedKontrakDetailId = id;
            selectedKontrakLaporanId = null;
            selectedLaporanId = null;

            // highlight
            document.querySelectorAll('#tableKontrakDetail tbody tr').forEach(tr => tr.classList.remove('selected-row'));
            rowElement.classList.add('selected-row');

            document.getElementById('badgeKontrakLaporanTerpilih').innerText = 'Detail Kontrak ID: ' + id;

            // reset laporan panels
            document.getElementById('badgeLaporanTerpilih').innerText = 'Belum ada laporan dipilih';
            const lapTbody = document.querySelector('#tableLaporan tbody');
            if (lapTbody) {
                lapTbody.querySelectorAll('tr').forEach(tr => tr.classList.remove('selected-row'));
            }
            document.querySelector('#tableLaporanDetail tbody').innerHTML = '';
            document.getElementById('btnSaveDetailLaporan').disabled = true;

            loadKontrakLaporan(id);
        }

        // ===============================
        // KONTRAK LAPORAN (by detail kontrak)
        // ===============================
        async function loadKontrakLaporan(kontrakDetailId) {
            const data = await apiGet(`${API_BASE}/kontraks/${kontrakDetailId}/laporans`);
            const tbody = document.querySelector('#tableKontrakLaporan tbody');
            tbody.innerHTML = '';

            data.forEach(kl => {
                const tr = document.createElement('tr');
                tr.classList.add('pointer');
                tr.dataset.id = kl.id;
                tr.dataset.laporanId = kl.laporan_id; // pastikan API mengembalikan laporan_id

                tr.innerHTML = `
                    <td>${kl.id}</td>
                    <td>${kl.bulan_tahun ?? '-'}</td>
                    <td>${kl.tgl_periode_mulai ?? ''} - ${kl.tgl_periode_selesai ?? ''}</td>
                    <td>${kl.is_lock ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete-kontrak-laporan" data-id="${kl.id}">Hapus</button>
                        <button class="btn btn-sm btn-primary btn-generate-laporan" data-id="${kl.id}" data-kontrak-detail-id="${kl.kontrak_detail_id}">Generate Laporan</button>
                    </td>
                `;

                tr.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-delete-kontrak-laporan')) return;
                    selectKontrakLaporanRow(kl.id, kl.laporan_id, tr);
                });

                // generate laporan berdasarkan kontrak laporan id di user
                tr.querySelector('.btn-generate-laporan').addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const kd_id = kl.kontrak_detail_id;
                    const kl_id = kl.id;
                    if (confirm('Yakin generate laporan untuk kontrak laporan ID ' + kl_id + ' ?')) {
                        await apiPost(`${API_BASE}/laporans/${kl_id}/${kd_id}`, {});
                        loadLaporan(kl.id, kl.laporan_id);
                    }
                });

                tbody.appendChild(tr);
            });

            document.querySelectorAll('.btn-delete-kontrak-laporan').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const id = btn.dataset.id;
                    if (confirm('Yakin hapus kontrak laporan ID ' + id + ' ?')) {
                        await apiDelete(`${API_BASE}/kontraks/${kontrakDetailId}/laporans/${id}`);
                        loadKontrakLaporan(kontrakDetailId);
                    }
                });
            });
        }

        function selectKontrakLaporanRow(kontrakLaporanId, laporanId, rowElement) {
            selectedKontrakLaporanId = kontrakLaporanId;
            selectedLaporanId = laporanId;

            // highlight kontrak_laporan
            document.querySelectorAll('#tableKontrakLaporan tbody tr').forEach(tr => tr.classList.remove('selected-row'));
            rowElement.classList.add('selected-row');

            document.getElementById('badgeLaporanTerpilih').innerText = 'Laporan ID: ' + laporanId;
            document.getElementById('btnSaveDetailLaporan').disabled = false;

            // load & highlight laporan terkait + detailnya
            loadLaporan(kontrakLaporanId);
        }

        // ===============================
        // LAPORAN (kanan)
        // ===============================
        async function loadLaporan(kontrakLaporanId, focusId = null) {
            const data = await apiGet(`${API_BASE}/laporans/${kontrakLaporanId}`);
            const tbody = document.querySelector('#tableLaporan tbody');
            tbody.innerHTML = '';

            data.forEach(l => {
                const tr = document.createElement('tr');
                tr.classList.add('pointer');
                tr.dataset.id = l.id;

                tr.innerHTML = `
                    <td>${l.id}</td>
                    <td>${l.kontrak_laporan.bulan_tahun ?? '-'}</td>
                    <td>${l.user.name ?? '-'}</td>
                    <td>${l.attachment_file_daily ?? '-'}</td>
                    <td>${l.is_lock ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete-laporan" data-id="${l.id}">Hapus</button>
                    </td>
                `;

                tr.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-delete-laporan')) return;
                    selectLaporanRow(l.id, tr);
                });

                tbody.appendChild(tr);
            });

            // tombol hapus laporan
            document.querySelectorAll('.btn-delete-laporan').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const id = btn.dataset.id;
                    if (confirm('Yakin hapus laporan ID ' + id + ' ?')) {
                        await apiDelete(`${API_BASE}/laporans/${id}`);
                        if (selectedLaporanId === parseInt(id)) {
                            selectedLaporanId = null;
                            document.getElementById('badgeLaporanTerpilih').innerText = 'Belum ada laporan dipilih';
                            document.querySelector('#tableLaporanDetail tbody').innerHTML = '';
                            document.getElementById('btnSaveDetailLaporan').disabled = true;
                        }
                        loadLaporan();
                    }
                });
            });

            // kalau dipanggil dengan focusId (dari KontrakLaporan)
            if (focusId) {
                const row = Array.from(document.querySelectorAll('#tableLaporan tbody tr'))
                    .find(r => r.dataset.id == focusId);
                if (row) {
                    selectLaporanRow(focusId, row);
                }
            }
        }

        function selectLaporanRow(id, rowElement) {
            selectedLaporanId = id;

            document.querySelectorAll('#tableLaporan tbody tr').forEach(tr => tr.classList.remove('selected-row'));
            rowElement.classList.add('selected-row');

            document.getElementById('badgeLaporanTerpilih').innerText = 'Laporan ID: ' + id;
            document.getElementById('btnSaveDetailLaporan').disabled = false;

            loadLaporanDetail(id);
        }

        // ===============================
        // DETAIL LAPORAN
        // ===============================
        async function loadLaporanDetail(laporanId) {
            const data = await apiGet(`${API_BASE}/laporandetails/${laporanId}`);
            const tbody = document.querySelector('#tableLaporanDetail tbody');
            tbody.innerHTML = '';

            data.forEach(d => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${d.id}</td>
                    <td>${d.judul_modul ?? '-'}</td>
                    <td>${d.judul_pekerjaan ?? '-'}</td>
                    <td>${d.progress_pekerjaan ?? '-'}</td>
                    <td><a href="/storage/${d.attachment_screenshots ?? '#'}" target="_blank">Lihat</a></td>
                    <td>${d.is_lock ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete-laporan-detail" data-id="${d.id}">Hapus</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            document.querySelectorAll('.btn-delete-laporan-detail').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    if (confirm('Yakin hapus detail laporan ID ' + id + ' ?')) {
                        await apiDelete(`${API_BASE}/laporandetails/${laporanId}/${id}`);
                        loadLaporanDetail(laporanId);
                    }
                });
            });
        }

        // ===============================
        // Submit FORM
        // ===============================
        // Kontrak
        document.getElementById('formKontrak').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const payload = Object.fromEntries(new FormData(form).entries());

            await apiPost(`${API_BASE}/kontraks`, payload);
            form.reset();
            loadKontrak();
        });

        // Detail Kontrak
        document.getElementById('formKontrakDetail').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!selectedKontrakId) {
                alert('Pilih kontrak terlebih dahulu.');
                return;
            }
            const form = e.target;
            const payload = Object.fromEntries(new FormData(form).entries());

            await apiPost(`${API_BASE}/kontraks/${selectedKontrakId}/details`, payload);
            form.reset();
            loadKontrakDetail(selectedKontrakId);
        });

        // Laporan (bebas dibuat, tidak tergantung klik KontrakLaporan)
        document.getElementById('formLaporan').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const payload = Object.fromEntries(new FormData(form).entries());

            await apiPost(`${API_BASE}/laporans`, payload);
            form.reset();
            loadLaporan();
        });

        // Detail Laporan
        document.getElementById('formLaporanDetail').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!selectedLaporanId) {
                alert('Pilih laporan terlebih dahulu.');
                return;
            }
            const form = e.target;

            await apiPostForm(`${API_BASE}/laporandetails/${selectedLaporanId}`, form);
            form.reset();
            loadLaporanDetail(selectedLaporanId);
        });

        // ===============================
        // INIT
        // ===============================
        document.addEventListener('DOMContentLoaded', () => {
            loadKontrak();
        });
    </script>
    @endpush
</x-app-layout>
