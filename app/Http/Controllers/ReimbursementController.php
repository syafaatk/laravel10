<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementController extends Controller
{
    public function __construct()
    {
        // Middleware untuk memastikan hanya admin yang bisa approve/reject
        $this->middleware('role:admin')->only(['approved', 'rejected', 'pending', 'printSelected']);
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $reimbursements = Reimbursement::with('user')->latest()->get();
        } else {
            $reimbursements = Reimbursement::where('user_id', $user->id)->latest()->get();
        }

        return view('reimbursements.index', compact('reimbursements'));
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
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'attachment_note' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

    public function approve(Reimbursement $reimbursement)
    {
        $reimbursement->update([
            'status' => 'approved',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('reimbursements.index')->with('success', 'Reimbursement approved.');
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