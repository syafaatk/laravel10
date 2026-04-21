
﻿# Gemini Code Assist - Changelog & Backlog

Dokumen ini mencatat perubahan yang telah diimplementasikan oleh Gemini Code Assist dan daftar tugas (backlog) untuk pengembangan selanjutnya.

---

## Changelog

### [21-04-2026] - Ekspor PDF dan Desain Ulang CV

-   **Perubahan:** Menambahkan fungsionalitas ekspor PDF dan memperbarui desain CV.
-   **Deskripsi:**
    -   **[Fitur] Ekspor CV ke Format PDF:** Menambahkan kemampuan untuk mengunduh CV sebagai file PDF melalui tombol "Download PDF". Menggunakan library `barryvdh/laravel-dompdf` untuk generasi PDF.
    -   **[Fitur] Template PDF Khusus:** Membuat view baru (`cv/pdf.blade.php`) yang dioptimalkan untuk rendering PDF, memastikan tata letak yang konsisten dan profesional.
    -   **[Perbaikan] Desain dan Tata Letak CV:** Memperbaiki tampilan pratinjau CV (`cv/show.blade.php`) untuk tata letak yang lebih bersih dan modern.

---

### [21-04-2026] - Peningkatan Fitur CV

-   **Perubahan:** Memperbarui Model, Controller, dan View untuk fitur CV.
-   **Deskripsi:**
    -   **[Fitur] Penambahan Detail Tambahan:** Menambahkan kolom dan input untuk URL LinkedIn, GitHub, dan Portofolio pada CV.
    -   **[Fitur] Penambahan Foto Profil:** Menampilkan foto profil pengguna yang sudah ada di sistem pada halaman pratinjau CV.
    -   **[Fitur] Perhitungan Durasi Kerja:** Menampilkan durasi masa kerja (tahun dan bulan) untuk setiap item pengalaman kerja.
    -   **[Perbaikan] Validasi Data:** Memperketat aturan validasi di sisi server untuk semua formulir pada halaman kelola CV untuk memastikan integritas data.

---
### [21-04-2026] - Fitur Curriculum Vitae (CV)

-   **Perubahan:** Menambahkan Model, View, Controller, dan Migrasi baru untuk fitur CV.
-   **Deskripsi:**
    -   Membuat halaman `cv/edit` yang memungkinkan pengguna untuk menambah, mengubah, dan menghapus data pribadi, riwayat pendidikan, pengalaman kerja, dan keahlian.
    -   Membuat halaman pratinjau CV di `cv/show` yang menampilkan data dalam format yang rapi dan siap cetak.
    -   Menambahkan relasi pada model `User` untuk menghubungkannya dengan data CV.
    -   Menambahkan rute web dan metode controller yang diperlukan untuk fungsionalitas CRUD pada setiap bagian CV.

---

### [21-04-2026] - Perbaikan Tampilan Cetak Lembur & Atasi Tabel Overlap

-   **Perubahan:** Mengubah total file `resources/views/lembur/print.blade.php`.
-   **Deskripsi:**
    -   Mengimplementasikan tata letak dua kolom pada halaman cetak lembur, serupa dengan halaman cetak cuti.
        -   **Kolom Kiri:** Menampilkan ringkasan detail pengajuan untuk referensi cepat (tidak ikut dicetak).
        -   **Kolom Kanan:** Menampilkan pratinjau surat tugas lembur yang siap dicetak.
    -   Menambahkan fungsi cetak khusus (`@media print`) yang hanya akan mencetak area surat tugas.
    -   Memperbaiki masalah tabel "Uraian Pekerjaan" yang tumpang tindih (overlap) dengan mengubah properti CSS `height` menjadi `min-height`. Hal ini memungkinkan tinggi baris untuk menyesuaikan diri dengan panjang konten.
    -   Menambahkan orientasi cetak `A4 landscape` pada CSS untuk hasil cetak yang optimal.

## Backlog

Berikut adalah daftar fitur atau perbaikan yang direncanakan untuk implementasi di masa mendatang:

-   **[Fitur] Unduh Surat Tugas Lembur sebagai PDF:**
    -   **Deskripsi:** Menambahkan tombol pada halaman detail atau cetak lembur untuk mengunduh surat tugas langsung sebagai file PDF, tanpa perlu melalui dialog cetak browser.

-   **[Fitur] Laporan Rekapitulasi Lembur:**
    -   **Deskripsi:** Membuat halaman baru untuk menampilkan laporan rekapitulasi lembur bulanan atau berdasarkan rentang tanggal untuk semua karyawan. Mirip dengan fungsionalitas laporan cuti.

-   **[Perbaikan] Filter pada Halaman Daftar Lembur:**
    -   **Deskripsi:** Menambahkan fungsionalitas filter pada halaman `lembur.index` untuk admin, agar bisa memfilter pengajuan lembur berdasarkan karyawan, rentang tanggal, atau status.

-   **[Fitur] Integrasi Data Pengalaman Kerja dari Kontrak:**
    -   **Deskripsi:** Mengembangkan fitur CV agar data pengalaman kerja dapat secara otomatis diambil dari data kontrak karyawan yang sudah ada dalam sistem, mengurangi redundansi input data.