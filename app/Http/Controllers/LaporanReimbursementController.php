<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\Reimbursement;
use Illuminate\Http\Request;

class LaporanReimbursementController extends Controller
{
    public function index()
    {
        return view('laporan-reimbursements.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:pending,approved,rejected',
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
        //
    }

    public function edit(LaporanReimbursement $laporanReimbursement)
    {
        //
    }

    public function update(Request $request, LaporanReimbursement $laporanReimbursement)
    {
        //
    }

    public function destroy(LaporanReimbursement $laporanReimbursement)
    {
        //
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
