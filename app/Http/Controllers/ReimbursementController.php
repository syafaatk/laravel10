<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use App\Models\LaporanReimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReimbursementController extends Controller
{
    public function __construct()
    {
        // Middleware untuk memastikan hanya admin yang bisa approve/reject
        $this->middleware('role:admin')->only(['approved', 'rejected', 'pending','done', 'printSelected']);
    }

    public function index()
    {
        

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $reimbursements = Reimbursement::with('user','laporanReimbursement')->get();
            $laporanReimbursements = LaporanReimbursement::orderBy('start_date','desc')->get();
        } else {
            $laporanReimbursements = LaporanReimbursement::orderBy('start_date','desc')->get();
            $reimbursements = Reimbursement::where('user_id', $user->id)->with('laporanReimbursement')->latest()->get();
        }
        return view('reimbursements.index', compact('reimbursements','laporanReimbursements'));
    }

    public function create()
    {
        return view('reimbursements.create');
    }

    public function destroy(Reimbursement $reimbursement)
    {
        // Hanya admin yang bisa menghapus
        if (Auth::user()->hasRole('admin')) {
            if ($reimbursement->attachment) {
                Storage::disk('public')->delete($reimbursement->attachment);
            }
            if ($reimbursement->attachment_note) {
                Storage::disk('public')->delete($reimbursement->attachment_note);
            }
            $reimbursement->delete();
            return redirect()->route('reimbursements.index')->with('success', 'Reimbursement deleted successfully.');
        }

        abort(403);
    }

    public function terbilang($angka) {
        $angka = abs($angka);
        $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($angka < 12) {
            $temp = " " . $baca[$angka];
        } else if ($angka < 20) {
            $temp = $this->terbilang($angka - 10) . " belas";
        } else if ($angka < 100) {
            $temp = $this->terbilang($angka / 10) . " puluh" . $this->terbilang($angka % 10);
        } else if ($angka < 200) {
            $temp = " seratus" . $this->terbilang($angka - 100);
        } else if ($angka < 1000) {
            $temp = $this->terbilang($angka / 100) . " ratus" . $this->terbilang($angka % 100);
        } else if ($angka < 2000) {
            $temp = " seribu" . $this->terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $temp = $this->terbilang($angka / 1000) . " ribu" . $this->terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $temp = $this->terbilang($angka / 1000000) . " juta" . $this->terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $temp = $this->terbilang($angka / 1000000000) . " milyar" . $this->terbilang(fmod($angka, 1000000000));
        } else if ($angka < 1000000000000000) {
            $temp = $this->terbilang($angka / 1000000000000) . " trilyun" . $this->terbilang(fmod($angka, 1000000000000));
        }
        return $temp;
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tipe' => 'required|in:1,2,3',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0|max:10000000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'attachment_note' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }
        $path_note = null;
        if ($request->hasFile('attachment_note')) {
            $path_note = $request->file('attachment_note')->store('attachments', 'public');
        }

        Reimbursement::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'tipe' => $request->tipe,
            'description' => $request->description,
            'amount' => $request->amount,
            'attachment' => $path,
            'attachment_note' => $path_note,
        ]);

        return redirect()->route('reimbursements.index')->with('success', 'Reimbursement request submitted successfully.');
    }

    public function show(Reimbursement $reimbursement)
    {
        // Otorisasi: User hanya bisa melihat miliknya, admin bisa melihat semua
        if (Auth::user()->hasRole('admin') || Auth::id() === $reimbursement->user_id) {
            return view('reimbursements.show', compact('reimbursement'));
        }

        abort(403);
    }

    public function approve(Request $request, Reimbursement $reimbursement)
    {
        // only admin
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $data = $request->validate([
            'laporan_id' => 'nullable',
            'new_laporan_title' => 'nullable|string|max:255',
            'new_laporan_start' => 'nullable|date',
            'new_laporan_end' => 'nullable|date',
        ]);

        // decide laporan
        $laporan = null;
        if (!empty($data['laporan_id']) && $data['laporan_id'] !== '__new__') {
            $laporan = LaporanReimbursement::find($data['laporan_id']);
        } elseif (!empty($data['new_laporan_title'])) {
            $laporan = LaporanReimbursement::create([
                'title' => $data['new_laporan_title'],
                'start_date' => $data['new_laporan_start'] ?? Carbon::now()->startOfMonth()->toDateString(),
                'end_date' => $data['new_laporan_end'] ?? Carbon::now()->endOfMonth()->toDateString(),
                // set other required fields or defaults if any
            ]);
        }

        // Associate reimbursement to laporan (one-to-many)
        if ($laporan) {
            $reimbursement->laporan_reimbursement_id = $laporan->id;
        } else {
            $reimbursement->laporan_reimbursement_id = null; // if admin wants no laporan
        }

        $reimbursement->status = 'approved';
        $reimbursement->processed_at = now();
        $reimbursement->processed_by = Auth::id();
        $reimbursement->save();

        return redirect()->back()->with('success', 'Reimbursement berhasil diajukan ke QT dan terkait ke laporan.');
    }

    public function pending(Reimbursement $reimbursement)
    {
        $reimbursement->update([
            'status' => 'pending',
            'processed_by' => null,
            'processed_at' => null,
        ]);

        return redirect()->route('reimbursements.index')->with('success', 'Reimbursement set to pending.');
    }

    public function reject(Reimbursement $reimbursement)
    {
        $reimbursement->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('reimbursements.index')->with('success', 'Reimbursement rejected.');
    }
    public function done(Reimbursement $reimbursement)
    {
        $reimbursement->update([
            'status' => 'done',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('reimbursements.index')->with('success', 'Reimbursement marked as done.');
    }

    public function downloadAttachment(Reimbursement $reimbursement)
    {
        // Otorisasi
        if (Auth::user()->hasRole('admin') || Auth::id() === $reimbursement->user_id) {
            return Storage::disk('public')->download($reimbursement->attachment);
        }

        abort(403);
    }

    public function downloadNote(Reimbursement $reimbursement)
    {
        // Otorisasi
        if (Auth::user()->hasRole('admin') || Auth::id() === $reimbursement->user_id) {
            return Storage::disk('public')->download($reimbursement->attachment_note);
        }

        abort(403);
    }

    public function printSelected(Request $request)
    {
        $request->validate([
            'reimbursement_ids' => 'required|array',
            'reimbursement_ids.*' => 'exists:reimbursements,id',
        ]);

        $reimbursements = Reimbursement::with(['user', 'processor'])
            ->whereIn('id', $request->reimbursement_ids)
            ->get();

        if ($reimbursements->isEmpty()) {
            return redirect()->back()->with('error', 'No reimbursements selected for printing.');
        }
        
        $report_data = [
            'total_amount' => $reimbursements->sum('amount'),
            'terbilang_total_amount' => $this->terbilang($reimbursements->sum('amount')),
        ];

        return view('reimbursements.print', compact('reimbursements', 'report_data'));
    }
}