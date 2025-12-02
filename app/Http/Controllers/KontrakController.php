<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Kontrak;
use App\Models\KontrakDetail;
use App\Models\KontrakLaporan;
use App\Models\Laporan;
use App\Models\LaporanDetail;
use App\Models\User;
use Carbon\Carbon;

class KontrakController extends Controller
{ 
    public function index()
    {
        $kontraks = Kontrak::all();
        return response()->json($kontraks);
    }

    public function store(Request $request)
    {
        $kontrak = Kontrak::create($request->all());
        return response()->json($kontrak, 201);
    }

    public function show($id)
    {
        $kontrak = Kontrak::find($id);
        return response()->json($kontrak);
    }

    public function update(Request $request, $id)
    {
        $kontrak = Kontrak::find($id);
        $kontrak->update($request->all());
        return response()->json($kontrak);
    }

    public function destroy($id)
    {
        Kontrak::destroy($id);
        return response()->json(null, 204);
    }

    public function indexDetail($kontrakId)
    {
        $details = KontrakDetail::where('kontrak_id', $kontrakId)->get();
        return response()->json($details);
    }

    public function storeDetail(Request $request, $kontrakId)
    {
        $detail = KontrakDetail::create(array_merge($request->all(), ['kontrak_id' => $kontrakId]));
        return response()->json($detail, 201);
    }

    public function indexKontrakLaporan($kontrakDetailId)
    {
        $laporans = KontrakLaporan::where('kontrak_detail_id', $kontrakDetailId)->get();
        return response()->json($laporans);
    }

    public function storeKontrakLaporan(Request $request, $kontrakDetailId)
    {
        $laporan = KontrakLaporan::create(array_merge($request->all(), ['kontrak_detail_id' => $kontrakDetailId]));
        return response()->json($laporan, 201);
    }

    public function destroyKontrakLaporan($kontrakDetailId, $laporanId)
    {
        KontrakLaporan::where('kontrak_detail_id', $kontrakDetailId)->where('id', $laporanId)->delete();
        return response()->json(null, 204);
    }

    public function generateKontrakLaporan($kontrakDetailId)
    {
        $kontrakDetail = KontrakDetail::find($kontrakDetailId);
        if (!$kontrakDetail) {
            return response()->json(['error' => 'Kontrak Detail not found'], 404);
        }

        // Ambil tgl_mulai_spph dan tgl_selesai_spph dari Kontrak Detail
        $contractStart = Carbon::parse($kontrakDetail->tgl_mulai_spph);
        $contractEnd   = Carbon::parse($kontrakDetail->tgl_selesai_spph);

        if ($contractStart->gt($contractEnd)) {
            return response()->json([
                'error' => 'Tanggal mulai SPPH lebih besar dari tanggal selesai SPPH'
            ], 422);
        }

        // Mulai dari tgl_mulai_spph
        $currentStart = $contractStart->copy();

        while ($currentStart->lte($contractEnd)) {

            // Periode selesai = +1 bulan - 1 hari (misal 22 Apr -> 21 Mei)
            $currentEnd = $currentStart->copy()->addMonthNoOverflow()->subDay();

            // Jika melewati akhir kontrak, potong ke tgl_selesai_spph
            if ($currentEnd->gt($contractEnd)) {
                $currentEnd = $contractEnd->copy();
            }

            // Bulan & tahun ambil dari tanggal mulai periode
            // Contoh: "April 2025"
            $bulanTahun = $currentStart->locale('id')->isoFormat('MMMM YYYY');

            // Cek apakah untuk periode ini sudah pernah dibuat
            $existing = KontrakLaporan::where('kontrak_detail_id', $kontrakDetailId)
                ->whereDate('tgl_periode_mulai', $currentStart->toDateString())
                ->first();

            if (!$existing) {
                KontrakLaporan::create([
                    'kontrak_detail_id'   => $kontrakDetailId,
                    'bulan_tahun'         => $bulanTahun,
                    'tgl_periode_mulai'   => $currentStart->toDateString(),
                    'tgl_periode_selesai' => $currentEnd->toDateString(),
                    'is_lock'             => false,
                ]);
            }

            // Next periode: hari setelah currentEnd
            $currentStart = $currentEnd->copy()->addDay();
        }

        return response()->json([
            'message' => 'Kontrak Laporan generated successfully'
        ], 201);
    }

    public function generateLaporan($kontrakLaporanId, $kontrakDetailId)
    {
        // Pastikan KontrakLaporan ada
        $kontrakLaporan = KontrakLaporan::find($kontrakLaporanId);
        if (!$kontrakLaporan) {
            return response()->json(['error' => 'Kontrak Laporan not found'], 404);
        }

        // Opsional: pastikan KontrakLaporan memang milik kontrak_detail_id yang dimaksud
        if ($kontrakLaporan->kontrak_detail_id != $kontrakDetailId) {
            return response()->json([
                'error' => 'Kontrak Laporan tidak sesuai dengan Kontrak Detail yang diberikan'
            ], 422);
        }

        // Ambil semua user yang punya kontrak_detail_id ini
        $users = User::where('kontrak_detail_id', $kontrakDetailId)->get();

        if ($users->isEmpty()) {
            return response()->json([
                'error' => 'Tidak ada user yang memiliki kontrak_detail_id ini'
            ], 404);
        }

        $created = [];
        $skipped = [];

        foreach ($users as $user) {
            // Cek apakah laporan untuk kombinasi (kontrak_laporan_id, user_id) sudah ada
            $existing = Laporan::where('kontrak_laporan_id', $kontrakLaporanId)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                $skipped[] = [
                    'user_id' => $user->id,
                    'laporan_id' => $existing->id,
                    'reason' => 'Laporan already exists for this Kontrak Laporan and User',
                ];
                continue;
            }

            // Buat Laporan baru
            $laporan = Laporan::create([
                'kontrak_laporan_id' => $kontrakLaporanId,
                'user_id'            => $user->id,
                'is_lock'            => false,
            ]);

            $created[] = $laporan;
        }

        return response()->json([
            'message' => 'Laporan generated successfully',
            'created' => $created,
            'skipped' => $skipped,
        ], 201);
    }



    public function indexLaporan($kontrakLaporanId)
    {
        $laporans = Laporan::with('user','kontrakLaporan')->where('kontrak_laporan_id', $kontrakLaporanId)->get();
        return response()->json($laporans);
    }

    public function storeLaporan(Request $request, $kontrakLaporanId)
    {
        $laporan = Laporan::create(array_merge($request->all(), ['kontrak_laporan_id' => $kontrakLaporanId]));
        return response()->json($laporan, 201);
    }

    public function indexLaporanDetail($laporanId)
    {
        $details = LaporanDetail::where('laporan_id', $laporanId)->get();
        return response()->json($details);
    }

    public function storeLaporanDetail(Request $request, $laporanId)
    {
        // Validasi
        $request->validate([
            'judul_modul'           => 'required|string|max:255',
            'judul_pekerjaan'       => 'required|string|max:255',
            'progress_pekerjaan'    => 'nullable|numeric',
            'is_lock'               => 'required|boolean',
            // jika multiple file: attachment_screenshots[]
            'attachment_screenshots'   => 'nullable',
            'attachment_screenshots.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only([
            'judul_modul',
            'judul_pekerjaan',
            'progress_pekerjaan',
            'is_lock',
        ]);
        $data['laporan_id'] = $laporanId;

        $paths = [];

        // CASE 1: multiple file -> name="attachment_screenshots[]"
        if ($request->hasFile('attachment_screenshots')) {
            $files = $request->file('attachment_screenshots');

            foreach ((array) $files as $file) {
                if ($file && $file->isValid()) {
                    $paths[] = $file->store('attachment_screenshots', 'public');
                }
            }
        }

        // CASE 2: single file -> name="attachment_screenshots"
        if ($request->hasFile('attachment_screenshots') && empty($paths)) {
            $file = $request->file('attachment_screenshots');
            if ($file->isValid()) {
                $paths[] = $file->store('attachment_screenshots', 'public');
            }
        }

        if (!empty($paths)) {
            // Kalau mau simpan semua path sebagai JSON:
            $data['attachment_screenshots'] = json_encode($paths);
            // atau kalau cuma satu file:
            $data['attachment_screenshots'] = $paths[0];
        }

        try {
            $detail = LaporanDetail::create($data);
            return response()->json($detail, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroyDetail($kontrakId, $detailId)
    {
        KontrakDetail::where('kontrak_id', $kontrakId)->where('id', $detailId)->delete();
        return response()->json(null, 204);
    }

    public function destroyLaporan($id)
    {
        Laporan::destroy($id);
        return response()->json(null, 204);
    }

    public function destroyLaporanDetail($laporanId, $detailId)
    {
        LaporanDetail::where('laporan_id', $laporanId)->where('id', $detailId)->delete();
        return response()->json(null, 204);
    }
}