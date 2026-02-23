<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\SlipGaji;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class GajiController extends Controller
{
    /**
     * Display a listing of the salary periods.
     */
    public function index()
    {
        // Gate::authorize('view-gaji');
        $gajiPeriods = Gaji::withCount('slipGaji')->orderBy('rentang_mulai', 'desc')->get();
        return view('admin.gaji.index', compact('gajiPeriods'));
    }

    /**
     * Show the form for creating a new salary period.
     */
    public function create()
    {
        // Gate::authorize('create-gaji');
        return view('admin.gaji.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Gate::authorize('create-gaji');
        $request->validate([
            'periode_bulan' => 'required|string|max:255',
            'rentang_mulai' => 'required|date',
            'rentang_selesai' => 'required|date|after_or_equal:rentang_mulai',
        ]);

        DB::beginTransaction();
        try {
            $gajiPeriod = Gaji::create([
                'periode_bulan' => $request->periode_bulan,
                'rentang_mulai' => $request->rentang_mulai,
                'rentang_selesai' => $request->rentang_selesai,
            ]);

            // Get active users who have not resigned before the end of the period
            $users = User::with('detailKontrakUserActive')
                ->whereHas('detailKontrakUserActive')
                ->whereDoesntHave('pengundurans', function($query) use ($gajiPeriod) {
                    $query->where('status', 'approved')
                          ->where('requested_date', '<=', $gajiPeriod->rentang_selesai);
                })
                ->get();

            foreach ($users as $user) {
                $kontrak = $user->detailKontrakUserActive;
                if (!$kontrak) continue; // Safety check

                // Explicitly calculate totals to ensure consistency with update logic
                $totalTunjangan = ($kontrak->tunjangan_jabatan ?? 0) +
                                  ($kontrak->tunjangan_golongan ?? 0) +
                                  ($kontrak->gaji_tunjangan_makan ?? 0) +
                                  ($kontrak->tunjangan_rumah ?? 0) +
                                  ($kontrak->gaji_tunjangan_transport ?? 0) +
                                  ($kontrak->tunjangan_tambahan ?? 0) +
                                  ($kontrak->tunjangan_extra ?? 0);

                $penghasilanBruto = ($kontrak->gaji_pokok ?? 0) + $totalTunjangan + ($kontrak->premi_jkk_jkm ?? 0);

                $totalPotongan = ($kontrak->potongan_pph21 ?? 0) + ($kontrak->potongan_jmo ?? 0); // potongan_lain is 0 initially

                $penghasilanNetto = $penghasilanBruto - $totalPotongan;

                // Snapshot the salary components into the payslip
                SlipGaji::create([
                    'gaji_id' => $gajiPeriod->id,
                    'user_id' => $user->id,
                    'gaji_pokok' => $kontrak->gaji_pokok ?? 0,
                    'tunjangan_jabatan' => $kontrak->tunjangan_jabatan ?? 0,
                    'tunjangan_golongan' => $kontrak->tunjangan_golongan ?? 0,
                    'tunjangan_makan' => $kontrak->gaji_tunjangan_makan ?? 0,
                    'tunjangan_rumah' => $kontrak->tunjangan_rumah ?? 0,
                    'tunjangan_transport' => $kontrak->gaji_tunjangan_transport ?? 0,
                    'tunjangan_tambahan' => $kontrak->tunjangan_tambahan ?? 0,
                    'tunjangan_extra' => $kontrak->tunjangan_extra ?? 0,
                    'premi_jkk_jkm' => $kontrak->premi_jkk_jkm ?? 0,
                    'potongan_pph21' => $kontrak->potongan_pph21 ?? 0,
                    'potongan_jmo' => $kontrak->potongan_jmo ?? 0,
                    'potongan_lain' => 0, // Default
                    'total_tunjangan' => $totalTunjangan,
                    'penghasilan_bruto' => $penghasilanBruto,
                    'total_potongan' => $totalPotongan,
                    'penghasilan_netto' => $penghasilanNetto,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal membuat periode gaji: ' . $e->getMessage());
            return redirect()->route('admin.gaji.index')->with('error', 'Terjadi kesalahan saat membuat periode gaji. Silakan coba lagi.');
        }
        return redirect()->route('admin.gaji.index')->with('success', 'Periode gaji berhasil dibuat dan slip gaji telah digenerate.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Gaji $gaji)
    {
        // Gate::authorize('view-gaji');
        $gaji->load('slipGaji.user');

        return view('admin.gaji.show', compact('gaji'));
    }
}