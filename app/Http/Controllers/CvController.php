<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cv;
use App\Models\Pendidikan;
use App\Models\PengalamanKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Keahlian;

class CvController extends Controller
{
    /**
     * Display the user's CV.
     */
    public function show()
    {
        $user = Auth::user()->load(['cv', 'pendidikans', 'pengalamanKerjas', 'keahlians']);
        return view('cv.show', compact('user'));
    }

    /**
     * Download the user's CV as a PDF.
     */
    public function downloadPdf()
    {
        $user = Auth::user()->load(['cv', 'pendidikans', 'pengalamanKerjas', 'keahlians']);
        $pdf = Pdf::loadView('cv.pdf', compact('user'));
        return $pdf->download('CV - ' . $user->name . '.pdf');
    }

    /**
     * Show the form for editing the user's CV.
     */
    public function edit()
    {
        $user = Auth::user()->load(['cv', 'pendidikans', 'pengalamanKerjas', 'keahlians']);
        return view('cv.edit', compact('user'));
    }

    /**
     * Update the user's personal summary.
     */
    public function update(Request $request)
    {
        $request->validate([
            'ringkasan_pribadi' => 'nullable|string|max:5000',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
        ]);

        $user = Auth::user();

        $cv = $user->cv()->firstOrCreate(
            ['user_id' => $user->id]
        );

        $cv->update([
            'ringkasan_pribadi' => $request->input('ringkasan_pribadi'),
            'linkedin_url' => $request->input('linkedin_url'),
            'github_url' => $request->input('github_url'),
            'portfolio_url' => $request->input('portfolio_url'),
        ]);

        return redirect()->route('cv.edit')->with('success', 'Ringkasan pribadi berhasil diperbarui.');
    }

    /**
     * Store a new education record.
     */
    public function storePendidikan(Request $request)
    {
        $request->validate([
            'jenjang' => 'required|string|max:50',
            'institusi' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tahun_lulus' => 'required|numeric|digits:4|min:1900|max:' . (date('Y') + 1),
        ]);

        Auth::user()->pendidikans()->create($request->all());

        return redirect()->route('cv.edit')->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    /**
     * Delete an education record.
     */
    public function destroyPendidikan($id)
    {
        $pendidikan = Pendidikan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pendidikan->delete();

        return redirect()->route('cv.edit')->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }

    /**
     * Store a new work experience record.
     */
    public function storePengalaman(Request $request)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string|max:5000',
        ]);

        Auth::user()->pengalamanKerjas()->create($request->all());

        return redirect()->route('cv.edit')->with('success', 'Pengalaman kerja berhasil ditambahkan.');
    }

    /**
     * Delete a work experience record.
     */
    public function destroyPengalaman($id)
    {
        $pengalaman = PengalamanKerja::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pengalaman->delete();

        return redirect()->route('cv.edit')->with('success', 'Pengalaman kerja berhasil dihapus.');
    }

    /**
     * Store a new skill record.
     */
    public function storeKeahlian(Request $request)
    {
        $request->validate([
            'nama_keahlian' => 'required|string|max:255',
            'tingkat' => 'required|in:Dasar,Menengah,Mahir,Ahli',
        ]);

        Auth::user()->keahlians()->create($request->all());

        return redirect()->route('cv.edit')->with('success', 'Keahlian berhasil ditambahkan.');
    }

    /**
     * Delete a skill record.
     */
    public function destroyKeahlian($id)
    {
        $keahlian = Keahlian::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $keahlian->delete();

        return redirect()->route('cv.edit')->with('success', 'Keahlian berhasil dihapus.');
    }
}