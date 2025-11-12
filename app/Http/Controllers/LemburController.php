<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use App\Models\DetailLembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class LemburController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $lemburs = Lembur::with(['user', 'approved'])->latest()->get();
        } else {
            $lemburs = Lembur::where('user_id', $user->id)->with(['user', 'approved'])->latest()->get();
        }
        return view('lembur.index', compact('lemburs'));
    }

    public function create()
    {
        return view('lembur.create');
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
            'estimasi_uang_lembur' => 'nullable|decimal',
            'uraian_pekerjaan.*' => 'required|string|max:1000',
        ]);

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = Carbon::parse($request->jam_selesai);
        // jika tipe weekend dan holiday maka durasiJam dikurang 1 jam
        if ($request->jenis == 'weekend' || $request->jenis == 'holiday') {
            $durasiJam = $jamSelesai->diffInHours($jamMulai) - 1;
        } else {
            $durasiJam = $jamSelesai->diffInHours($jamMulai);
        }

        // logic perhitungan estimasi uang lembur
        $estimasi_uang_lembur = 0;	
        //weekday
        // Jam Pertama: 1,5 x upah 1 Jam				1,5 x 1/173 x Upah Sebulan	
        // Jam Kedua dst: 2 x upah 1 Jam				2 x 1/173 x Upah Sebulan	
                                            
        //weekend
        // Jam Pertama s.d. Jam Kedelapan: 2 x upah 1 Jam				2 x 1/173 x Upah Sebulan	
        // Jam Kesembilan dst.: 3 x upah 1 Jam				3 x 1/173 x Upah Sebulan	
                                            
        //holiday
        // Jam Pertama s.d. Jam Kedelapan: 3 x upah 1 Jam				3 x 1/173 x Upah Sebulan	

        // Asumsi upah sebulan adalah 3.000.000 (contoh)
        // Anda bisa mendapatkan ini dari model User atau konfigurasi lain
        $upahSebulan =Auth::user()->gaji_pokok + Auth::user()->gaji_tunjangan_makan + Auth::user()->gaji_tunjangan_tetap  ?? 9000000; // Default jika tidak ada gaji pokok
        $upahPerJam = $upahSebulan / 173; // 1/173 x Upah Sebulan

        if ($request->jenis == 'weekday') {
            if ($durasiJam == 1) {
                $estimasi_uang_lembur = 1.5 * $upahPerJam;
            } elseif ($durasiJam > 1) {
                $estimasi_uang_lembur = (1.5 * $upahPerJam) + (($durasiJam - 1) * 2 * $upahPerJam);
            }
        } elseif ($request->jenis == 'weekend') {
            if ($durasiJam >= 1 && $durasiJam <= 8) {
                $estimasi_uang_lembur = $durasiJam * 2 * $upahPerJam;
            } elseif ($durasiJam > 8) {
                $estimasi_uang_lembur = (8 * 2 * $upahPerJam) + (($durasiJam - 8) * 3 * $upahPerJam);
            }
        } elseif ($request->jenis == 'holiday') {
            $estimasi_uang_lembur = $durasiJam * 3 * $upahPerJam;
        }

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
            DetailLembur::create([
                'lembur_id' => $lembur->id,
                'uraian_pekerjaan' => $uraian,
            ]);
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

        $upahSebulan = Auth::user()->gaji_pokok + Auth::user()->gaji_tunjangan_makan + Auth::user()->gaji_tunjangan_tetap  ?? 9000000;
        $upahPerJam = $upahSebulan / 173; 
        $estimasi_uang_lembur = 0;

        if ($request->jenis == 'weekday') {
            if ($durasiJam == 1) {
                $estimasi_uang_lembur = 1.5 * $upahPerJam;
            } elseif ($durasiJam > 1) {
                $estimasi_uang_lembur = (1.5 * $upahPerJam) + (($durasiJam - 1) * 2 * $upahPerJam);
            }
        } elseif ($request->jenis == 'weekend') {
            if ($durasiJam >= 1 && $durasiJam <= 8) {
                $estimasi_uang_lembur = $durasiJam * 2 * $upahPerJam;
            } elseif ($durasiJam > 8) {
                $estimasi_uang_lembur = (8 * 2 * $upahPerJam) + (($durasiJam - 8) * 3 * $upahPerJam);
            }
        } elseif ($request->jenis == 'holiday') {
            $estimasi_uang_lembur = $durasiJam * 3 * $upahPerJam;
        }

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
            DetailLembur::create([
                'lembur_id' => $lembur->id,
                'uraian_pekerjaan' => $uraian,
            ]);
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
    
}