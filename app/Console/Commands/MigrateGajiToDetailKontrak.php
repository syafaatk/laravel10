<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\DetailKontrakUser;
use Illuminate\Console\Command;

class MigrateGajiToDetailKontrak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:gaji-to-kontrak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate gaji data from users table to detail_kontrak_users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of gaji data...');
        
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            // Cek apakah user sudah memiliki data kontrak aktif
            if ($user->detailKontrakUsers()->where('is_active', true)->exists()) {
                $this->warn("User {$user->name} sudah memiliki kontrak aktif, skip...");
                continue;
            }

            // Create new detail kontrak
            DetailKontrakUser::create([
                'user_id' => $user->id,
                'kontrak' => $user->kontrak,
                'tgl_mulai_kontrak' => $user->tgl_masuk,
                'tgl_selesai_kontrak' => null, // Set manually jika ada
                'gaji_pokok' => $user->gaji_pokok ?? 0,
                'gaji_tunjangan_tetap' => $user->gaji_tunjangan_tetap ?? 0,
                'gaji_tunjangan_makan' => $user->gaji_tunjangan_makan ?? 0,
                'gaji_tunjangan_transport' => $user->gaji_tunjangan_transport ?? 0,
                'gaji_tunjangan_lain' => $user->gaji_tunjangan_lain ?? 0,
                'gaji_bpjs' => $user->gaji_bpjs ?? 0,
                'is_active' => true,
            ]);

            $count++;
        }

        $this->info("Migration completed! {$count} records migrated.");
    }
}