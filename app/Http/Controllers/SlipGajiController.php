<?php

namespace App\Http\Controllers;

use App\Models\SlipGaji;
use App\Models\Gaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SlipGajiController extends Controller
{
    /**
     * Display a listing of the user's payslips.
     */
    public function index()
    {
        $slipGajis = SlipGaji::where('user_id', auth()->id())
            ->with('gaji')
            ->orderByDesc(
                Gaji::select('rentang_mulai')->whereColumn('gajis.id', 'slip_gajis.gaji_id')
            )
            ->get();

        return view('slip-gaji.index', compact('slipGajis'));
    }

    /**
     * Display the specified payslip.
     */
    public function show(SlipGaji $slipGaji)
    {
        // Ensure the user is authorized to view this slip
        // If not admin, they can only see their own slip
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $slipGaji->user_id) {
            abort(403);
        }

        $slipGaji->load(['user', 'gaji']);
        
        return view('admin.gaji.slip', compact('slipGaji'));
    }

    /**
     * Print the specified payslip.
     */
    public function print(SlipGaji $slipGaji)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $slipGaji->user_id) {
            abort(403);
        }

        $slipGaji->load(['user', 'gaji']);
        
        return view('admin.gaji.slip', compact('slipGaji'))->with('print', true);
    }

    /**
     * Update the specified payslip in storage.
     */
    public function update(Request $request, SlipGaji $slipGaji)
    {
        // Pastikan hanya admin yang dapat mengubah
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mengubah slip gaji.');
        }

        $validatedData = $request->validate([
            'catatan' => 'nullable|string',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'required|numeric|min:0',
            'tunjangan_golongan' => 'required|numeric|min:0',
            'tunjangan_makan' => 'required|numeric|min:0',
            'tunjangan_rumah' => 'required|numeric|min:0',
            'tunjangan_transport' => 'required|numeric|min:0',
            'tunjangan_tambahan' => 'required|numeric|min:0',
            'tunjangan_extra' => 'required|numeric|min:0',
            'premi_jkk_jkm' => 'required|numeric|min:0',
            'potongan_pph21' => 'required|numeric|min:0',
            'potongan_jmo' => 'required|numeric|min:0',
            'potongan_lain' => 'required|numeric|min:0',
        ]);

        // Calculate total tunjangan
        $totalTunjangan = $validatedData['tunjangan_jabatan'] +
                          $validatedData['tunjangan_golongan'] +
                          $validatedData['tunjangan_makan'] +
                          $validatedData['tunjangan_rumah'] +
                          $validatedData['tunjangan_transport'] +
                          $validatedData['tunjangan_tambahan'] +
                          $validatedData['tunjangan_extra'];

        // Calculate penghasilan bruto (gaji pokok + semua tunjangan + premi)
        $penghasilanBruto = $validatedData['gaji_pokok'] + $totalTunjangan + $validatedData['premi_jkk_jkm'];

        // Calculate total potongan
        $totalPotongan = $validatedData['potongan_pph21'] +
                         $validatedData['potongan_jmo'] +
                         $validatedData['potongan_lain'];

        // Calculate penghasilan netto
        $penghasilanNetto = $penghasilanBruto - $totalPotongan;

        $updateData = array_merge($validatedData, [
            'total_tunjangan' => $totalTunjangan,
            'penghasilan_bruto' => $penghasilanBruto,
            'total_potongan' => $totalPotongan,
            'penghasilan_netto' => $penghasilanNetto,
        ]);

        $slipGaji->update($updateData);

        return redirect()->route('admin.slip-gaji.show', $slipGaji->id)->with('success', 'Slip gaji berhasil diperbarui.');
    }
}