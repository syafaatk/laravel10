<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\Reimbursement;
use App\Models\LaporanReimbursement;
use Illuminate\Http\Request;

class LaporanReimbursementController extends Controller
{
    public function index()
    {
        $laporanReimbursements = LaporanReimbursement::with('user')->latest()->paginate(10);
        return view('laporan-reimbursements.index', compact('laporanReimbursements'));
    }

    public function search()
    {
        return view('laporan-reimbursements.search');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:pending,approved,done,rejected',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $reimbursements = Reimbursement::whereBetween('created_at', [$startDate, $endDate])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->with(['user', 'processor'])
            ->get();

        $report_data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $request->status,
        ];
        $report_data['reimbursements'] = $reimbursements;
        $report_data['total_amount'] = $reimbursements->sum('amount');
        $report_data['terbilang_total_amount'] = $this->terbilang($report_data['total_amount']);
        $report_data['form_number_month'] = $this->form_number_month(Carbon::now()->month);
        $report_data['attachment'] = null;

        return view('reimbursements.print', compact('reimbursements', 'report_data'));
    }

    public function terbilang($angka) {
        $angka = abs($angka);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($angka < 12) {
            $temp = " " . $huruf[$angka];
        } else if ($angka < 20) {
            $temp = $this->terbilang($angka - 10) . " Belas";
        } else if ($angka < 100) {
            $temp = $this->terbilang($angka / 10) . " Puluh" . $this->terbilang($angka % 10);
        } else if ($angka < 200) {
            $temp = " Seratus" . $this->terbilang($angka - 100);
        } else if ($angka < 1000) {
            $temp = $this->terbilang($angka / 100) . " Ratus" . $this->terbilang($angka % 100);
        } else if ($angka < 2000) {
            $temp = " Seribu" . $this->terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $temp = $this->terbilang($angka / 1000) . " Ribu" . $this->terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $temp = $this->terbilang($angka / 1000000) . " Juta" . $this->terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $temp = $this->terbilang($angka / 1000000000) . " Milyar" . $this->terbilang(fmod($angka, 1000000000));
        } else if ($angka < 1000000000000000) {
            $temp = $this->terbilang($angka / 1000000000000) . " Trilyun" . $this->terbilang(fmod($angka, 1000000000000));
        }
        return $temp;
    }

    public function show(LaporanReimbursement $laporanReimbursement)
    {
        return view('laporan-reimbursements.show', compact('laporanReimbursement'));
    }

    public function edit(LaporanReimbursement $laporanReimbursement)
    {
        return view('laporan-reimbursements.edit', compact('laporanReimbursement'));
    }

    public function update(Request $request, LaporanReimbursement $laporanReimbursement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:pending,approved,done,rejected',
            'attachment' => 'nullable|file|mimes:pdf|max:20480', // Only PDF for reports
        ]);

        $laporanReimbursement->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'amount' => $request->amount,
            'attachment' => $path ?? $laporanReimbursement->attachment,
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($laporanReimbursement->attachment) {
                \Storage::disk('public')->delete($laporanReimbursement->attachment);
            }
            $path = $request->file('attachment')->store('laporan_attachments', 'public');
        }

        return redirect()->route('admin.laporan-reimbursements.index')->with('success', 'Laporan Reimbursement updated successfully.');
        
    }

    public function destroy(LaporanReimbursement $laporanReimbursement)
    {
        $laporanReimbursement->delete();
        return redirect()->route('admin.laporan-reimbursements.index')->with('success', 'Laporan Reimbursement deleted successfully.');
    }
    
    public function create()
    {
        $users = \App\Models\User::all(); // Assuming you want to select from all users
        return view('laporan-reimbursements.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:pending,approved,done,rejected',
            'attachment' => 'nullable|file|mimes:pdf|max:20480', // Only PDF for reports
            'amount' => 'nullable|integer|min:0|max:1000000000',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('laporan_attachments', 'public');
        }

        $user_id = $request->user_id ?? auth()->id();

        LaporanReimbursement::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => $user_id,
            'status' => $request->status,
            'attachment' => $path,
        ]);

        return redirect()->route('admin.laporan-reimbursements.index')->with('success', 'Laporan Reimbursement created successfully.');
    }
    
    public function form_number_month($month)
    {
        $months = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $months[$month] ?? '';
    }
    
}
