<?php

return [
    // Libur Nasional Indonesia 2024-2025
    'holidays' => [
        // 2024
        '2024-01-01', // Tahun Baru
        '2024-02-08', // Isra dan Miraj
        '2024-02-14', // Hari Valentine
        '2024-02-29', // Hari Kesadaran Pembaca
        '2024-03-11', // Nyepi
        '2024-03-29', // Jumat Agung
        '2024-03-30', // Hari Raya Idul Fitri (Libur Bersama 1)
        '2024-03-31', // Hari Raya Idul Fitri (Libur Bersama 2)
        '2024-04-01', // Hari Raya Idul Fitri (Libur Bersama 3)
        '2024-04-09', // Hari Raya Idul Adha
        '2024-04-10', // Tahun Baru Hijriah
        '2024-05-01', // Hari Buruh
        '2024-05-23', // Kenaikan Isa Almasih
        '2024-05-29', // Hari Raya Waisak
        '2024-06-01', // Hari Lahir Pancasila
        '2024-06-17', // Cuti Bersama (Mawlid Nabi Muhammad)
        '2024-08-17', // Hari Kemerdekaan RI
        '2024-12-25', // Natal
        '2024-12-26', // Cuti Bersama
        '2024-12-31', // Cuti Bersama

        // 2025
        '2025-01-01', // Tahun Baru
        '2025-01-29', // Isra dan Miraj
        '2025-02-14', // Hari Valentine
        '2025-03-01', // Hari Kesadaran Pembaca
        '2025-03-03', // Nyepi
        '2025-04-18', // Jumat Agung
        '2025-04-02', // Hari Raya Idul Fitri (Libur Bersama 1)
        '2025-04-03', // Hari Raya Idul Fitri (Libur Bersama 2)
        '2025-04-04', // Hari Raya Idul Fitri (Libur Bersama 3)
        '2025-04-07', // Hari Raya Idul Fitri (Libur Bersama 4)
        '2025-04-30', // Hari Raya Idul Adha
        '2025-05-01', // Hari Buruh
        '2025-05-09', // Kenaikan Isa Almasih
        '2025-05-19', // Tahun Baru Hijriah
        '2025-05-22', // Hari Raya Waisak
        '2025-06-01', // Hari Lahir Pancasila
        '2025-06-02', // Cuti Bersama (Mawlid Nabi Muhammad)
        '2025-08-17', // Hari Kemerdekaan RI
        '2025-12-25', // Natal
        '2025-12-26', // Cuti Bersama

        //2026
        '2026-01-01', // Tahun Baru
        '2026-01-16', // Isra dan Miraj
        '2026-02-17', // Tahun Baru Imlek
        '2026-03-19', // Hari Suci Nyepi
        '2026-03-21', // Hari Raya Idul Fitri (Libur)
        '2026-04-03', // Wafat Yesus Kristus
        '2026-04-05', // Kebangkitan Yesus Kristus
        '2026-05-01', // Hari Buruh
        '2026-05-14', // Kenaikan Isa Almasih
        '2026-05-27', // Hari Raya Idul Adha
        '2026-06-01', // Hari Lahir Pancasila
        '2026-06-16', // Tahun Baru Islam
        '2026-08-17', // Hari Kemerdekaan RI
        '2026-08-25', // Maulid Nabi Muhammad SAW
        '2026-12-25', // Natal
    ],

    // Cuti Bersama tambahan (opsional - jika berbeda dari holidays)
    'cuti_bersama' => [
        // Biasanya cuti bersama sudah termasuk di holidays array
        // Jika ada cuti bersama khusus tambahan, list di sini
        // Contoh:
        '2026-03-18', // Cuti Bersama tambahan
        '2026-03-20', // Cuti Bersama tambahan
        '2026-03-23', // Cuti Bersama tambahan
        '2026-03-24', // Cuti Bersama tambahan
        '2026-05-15', // Cuti Bersama tambahan
        '2026-05-28', // Cuti Bersama tambahan
        '2026-12-24', // Cuti Bersama tambahan
    ],

    // Hari libur per minggu (Sabtu dan Minggu sudah otomatis di-skip di controller)
    // Jika ada hari libur custom per minggu (misal: ada shift work), define di sini
    'weekend_days' => [6, 0], // 6 = Sabtu, 0 = Minggu (Carbon day of week)
];