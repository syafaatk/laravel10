<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lembur;
use App\Models\DetailLembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('view-user')) {
            abort(403);
        }

        $user = Auth::user();
        $query = Lembur::with(['user', 'approved']);

        if ($user->hasRole('admin')) {
            // Admin filters
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }
        } else {
            // Non-admin only sees their own
            $query->where('user_id', $user->id);
        }

        $lemburs = $query->latest()->get();
        
        // Data untuk filter
        $users = $user->hasRole('admin') ? User::orderBy('name')->get() : [];

        return view('lembur.index', compact('lemburs', 'users'));
    }

    public function create()
    {
        return view('lembur.create');
    }

    public function revisi( $id)
    {
        $lembur = Lembur::findOrFail($id);
        if (Auth::id() !== $lembur->user_id) {
            abort(403);
        }
        return view('lembur.edit', compact('lembur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:weekday,weekend,holiday',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:1000',
            'approver' => 'nullable|string|max:255',
            'uraian_pekerjaan.*' => 'required|string|max:1000',
        ]);

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = Carbon::parse($request->jam_selesai);
        
        // Jika tipe weekend dan holiday maka durasiJam dikurang 1 jam
        if ($request->jenis == 'weekend' || $request->jenis == 'holiday') {
            $durasiJam = $jamSelesai->diffInHours($jamMulai) - 1;
        } else {
            $durasiJam = $jamSelesai->diffInHours($jamMulai);
        }

        // Get upah sebulan dari detail kontrak aktif user
        $user = Auth::user();
        $detailKontrak = $user->detailKontrakUserActive;
        
        if (!$detailKontrak) {
            return redirect()->back()->with('error', 'User belum memiliki kontrak aktif. Silakan hubungi admin.');
        }

        $upahSebulan = $detailKontrak->gaji_pokok + 
                       $detailKontrak->gaji_tunjangan_tetap + 
                       $detailKontrak->gaji_tunjangan_makan;
        
        $upahPerJam = $upahSebulan / 173; // 1/173 x Upah Sebulan

        // Logic perhitungan estimasi uang lembur
        $estimasi_uang_lembur = $this->hitungUangLembur($request->jenis, $durasiJam, $upahPerJam);

        $lembur = Lembur::create([
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_jam' => $durasiJam,
            'keterangan' => $request->keterangan,
            'approver' => $request->approver,
            'estimasi_uang_lembur' => $estimasi_uang_lembur,
            'status' => 'pending',
        ]);

        foreach ($request->uraian_pekerjaan as $uraian) {
            if (!empty($uraian)) {
                DetailLembur::create([
                    'lembur_id' => $lembur->id,
                    'uraian_pekerjaan' => $uraian,
                ]);
            }
        }

        return redirect()->route('lembur.index')->with('success', 'Pengajuan lembur berhasil disimpan.');
    }

    public function show(Lembur $lembur)
    {
        if (Auth::user()->hasRole('admin') || Auth::id() === $lembur->user_id) {
            return view('lembur.show', compact('lembur'));
        }
        abort(403);
    }

    public function edit(Lembur $lembur)
    {
        if (Auth::id() !== $lembur->user_id) {
            abort(403);
        }
        return view('lembur.edit', compact('lembur'));
    }

    public function update(Request $request, Lembur $lembur)
    {
        if (Auth::id() !== $lembur->user_id) {
            abort(403);
        }
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:weekday,weekend,holiday',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:1000',
            'uraian_pekerjaan.*' => 'required|string|max:1000',
        ]);

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = Carbon::parse($request->jam_selesai);
        
        if ($request->jenis == 'weekend' || $request->jenis == 'holiday') {
            $durasiJam = $jamSelesai->diffInHours($jamMulai) - 1;
        } else {
            $durasiJam = $jamSelesai->diffInHours($jamMulai);
        }

        // Get upah sebulan dari detail kontrak aktif user
        $user = Auth::user();
        $detailKontrak = $user->detailKontrakUserActive;
        
        if (!$detailKontrak) {
            return redirect()->back()->with('error', 'User belum memiliki kontrak aktif. Silakan hubungi admin.');
        }

        $upahSebulan = $detailKontrak->gaji_pokok + 
                       $detailKontrak->gaji_tunjangan_tetap + 
                       $detailKontrak->gaji_tunjangan_makan;
        
        $upahPerJam = $upahSebulan / 173;
        
        $estimasi_uang_lembur = $this->hitungUangLembur($request->jenis, $durasiJam, $upahPerJam);

        $lembur->update([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_jam' => $durasiJam,
            'keterangan' => $request->keterangan,
            'estimasi_uang_lembur' => $estimasi_uang_lembur,
            'status' => 'pending', // Reset status to pending after edit
        ]);

        // Update detail lembur
        $lembur->detailLemburs()->delete(); // Hapus semua detail lama
        foreach ($request->uraian_pekerjaan as $uraian) {
            if (!empty($uraian)) {
                DetailLembur::create([
                    'lembur_id' => $lembur->id,
                    'uraian_pekerjaan' => $uraian,
                ]);
            }
        }

        return redirect()->route('lembur.index')->with('success', 'Pengajuan lembur berhasil diperbarui.');
    }

    public function destroy(Lembur $lembur)
    {
        if (Auth::id() !== $lembur->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        $lembur->delete();
        return redirect()->route('lembur.index')->with('success', 'Pengajuan lembur berhasil dihapus.');
    }

    public function approve(Lembur $lembur)
    {
        Gate::authorize('approve-lembur');

        $lembur->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('lembur.index')->with('success', 'Lembur disetujui.');
    }

    public function reject(Lembur $lembur)
    {
        Gate::authorize('approve-lembur');

        $lembur->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('lembur.index')->with('success', 'Lembur ditolak.');
    }

    public function print(Lembur $lembur)
    {
        if (Auth::user()->hasRole('admin') || Auth::id() === $lembur->user_id) {
            return view('lembur.print', compact('lembur'));
        }
        abort(403);
    }

    /**
     * Hitung uang lembur berdasarkan jenis dan durasi
     * 
     * @param string $jenis (weekday, weekend, holiday)
     * @param int $durasiJam
     * @param float $upahPerJam
     * @return float
     */
    private function hitungUangLembur($jenis, $durasiJam, $upahPerJam)
    {
        $estimasi = 0;

        /**
         * Perhitungan Lembur:
         * 
         * WEEKDAY:
         * - Jam Pertama: 1,5 x upah 1 jam
         * - Jam Kedua dst: 2 x upah 1 jam
         * 
         * WEEKEND:
         * - Jam 1-8: 2 x upah 1 jam
         * - Jam 9 dst: 3 x upah 1 jam
         * 
         * HOLIDAY:
         * - Semua jam: 3 x upah 1 jam
         */

        if ($jenis == 'weekday') {
            if ($durasiJam == 1) {
                $estimasi = 1.5 * $upahPerJam;
            } elseif ($durasiJam > 1) {
                $estimasi = (1.5 * $upahPerJam) + (($durasiJam - 1) * 2 * $upahPerJam);
            }
        } elseif ($jenis == 'weekend') {
            if ($durasiJam >= 1 && $durasiJam <= 8) {
                $estimasi = $durasiJam * 2 * $upahPerJam;
            } elseif ($durasiJam > 8) {
                $estimasi = (8 * 2 * $upahPerJam) + (($durasiJam - 8) * 3 * $upahPerJam);
            }
        } elseif ($jenis == 'holiday') {
            $estimasi = $durasiJam * 3 * $upahPerJam;
        }

        return round($estimasi, 0);
    }

    /**
     * Download overtime assignment letter as PDF.
     */
    public function downloadPdf(Lembur $lembur)
    {
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $lembur->user_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('lembur.pdf', compact('lembur'))->setPaper('a4', 'landscape');
        return $pdf->download('Surat Tugas Lembur - ' . $lembur->user->name . ' - ' . $lembur->tanggal->format('d-m-Y') . '.pdf');
    }

    /**
     * Show the search form for the overtime report.
     */
    public function searchReport()
    {
        Gate::authorize('view-admin-reports'); // Anda mungkin perlu membuat Gate ini
        return view('lembur.report-search');
    }

    /**
     * Generate and display the overtime report.
     */
    public function generateReport(Request $request)
    {
        Gate::authorize('view-admin-reports');

        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $month = $request->month;
        $year = $request->year;

        $reportData = Lembur::where('status', 'approved')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->with('user')
            ->get();

        return view('lembur.report', compact('reportData', 'month', 'year'));
    }
}