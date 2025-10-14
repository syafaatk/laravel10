<?php

namespace App\Http\Controllers;

use App\Models\PenilaianPegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenilaianPegawaiController extends Controller
{
    // Kriteria penilaian yang akan digunakan
    const CRITERIA = [
        'quality_of_work' => 'Kualitas Kerja',
        'quantity_of_work' => 'Kuantitas Kerja',
        'initiative' => 'Inisiatif',
        'discipline' => 'Disiplin',
        'responsibility' => 'Tanggungjawab',
        'motivation' => 'Motivasi',
        'teamwork' => 'Kerjasama',
        'task_understanding' => 'Pemahaman terhadap tugas',
        'adaptability' => 'Penyesuaian diri',
        'overall_performance' => 'Kinerja Keseluruhan',
    ];

    public function index()
    {
        $penilaians = PenilaianPegawai::with(['user', 'reviewer'])->latest()->get();
        return view('admin.penilaian.index', compact('penilaians'));
    }

    public function create()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();
        $criteria = self::CRITERIA;
        return view('admin.penilaian.create', compact('users', 'criteria'));
    }

    public function print(PenilaianPegawai $penilaian)
    {
        $criteria = self::CRITERIA;
        $penilaian->scores = collect($penilaian->scores)->map(function ($score, $key) use ($criteria) {
            $criteriaLabel = $criteria[$key] ?? $key;
            return (object) ['criteria_name' => $criteriaLabel, 'score_value' => $score];
        });
        $report_data['form_number_month'] = $this->form_number_month(Carbon::now()->month);
        return view('admin.penilaian.print', compact('penilaian', 'criteria', 'report_data'));
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
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'review_date' => 'required|date',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'feedback' => 'nullable|string',
        ]);

        // Hitung skor rata-rata
        $overall_score = collect($validated['scores'])->average();

        PenilaianPegawai::create(array_merge($validated, [
            'reviewer_id' => Auth::id(),
            'overall_score' => $overall_score,
        ]));

        return redirect()->route('admin.penilaian.index')->with('success', 'Penilaian pegawai berhasil disimpan.');
    }

    public function show(PenilaianPegawai $penilaian)
    {
        $criteria = self::CRITERIA;
        $penilaian->scores = collect($penilaian->scores)->map(function ($score, $key) use ($criteria) {
            $criteriaLabel = $criteria[$key] ?? $key;
            return (object) ['criteria_name' => $criteriaLabel, 'score_value' => $score];
        });
        // dd($penilaian->scores);
        return view('admin.penilaian.show', compact('penilaian', 'criteria'));
    }

    public function edit(PenilaianPegawai $penilaian)
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        $criteria = self::CRITERIA;
        return view('admin.penilaian.edit', compact('penilaian', 'users', 'criteria'));
    }

    public function update(Request $request, PenilaianPegawai $penilaian)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'review_date' => 'required|date',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'feedback' => 'nullable|string',
        ]);

        $overall_score = collect($validated['scores'])->average();

        $penilaian->update(array_merge($validated, [
            'reviewer_id' => Auth::id(),
            'overall_score' => $overall_score,
        ]));

        return redirect()->route('admin.penilaian.index')->with('success', 'Penilaian pegawai berhasil diperbarui.');
    }

    public function destroy(PenilaianPegawai $penilaian)
    {
        $penilaian->delete();
        return redirect()->route('admin.penilaian.index')->with('success', 'Penilaian pegawai berhasil dihapus.');
    }
}
