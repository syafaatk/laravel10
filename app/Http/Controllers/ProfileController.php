<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\DetailKontrakUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $detailKontrakAktif = $user->detailKontrakUserActive;
        
        return view('profile.edit', compact('user', 'detailKontrakAktif'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update basic user info
        $user->address = $request->input('address');
        $user->no_wa = $request->input('no_wa');
        $user->motor = $request->input('motor');
        $user->ukuran_baju = $request->input('ukuran_baju');
        $user->tgl_masuk = $request->input('tgl_masuk');
        $user->nopeg = $request->input('nopeg');
        $user->jabatan = $request->input('jabatan');
        $user->norek = $request->input('norek');
        $user->bank = $request->input('bank');

        // Handle file uploads
        if ($request->hasFile('attachment_ttd')) {
            if ($user->attachment_ttd) {
                Storage::delete('public/' . $user->attachment_ttd);
            }
            $user->attachment_ttd = $request->file('attachment_ttd')->store('attachments_ttd', 'public');
        }

        if ($request->hasFile('attachment_foto_profile')) {
            if ($user->attachment_foto_profile) {
                Storage::delete('public/' . $user->attachment_foto_profile);
            }
            $user->attachment_foto_profile = $request->file('attachment_foto_profile')->store('attachments_foto_profile', 'public');
        }

        $user->save();

        // Logic untuk update kontrak
        $detailKontrakAktif = $user->detailKontrakUserActive;
        $tglMulaiKontrakBaru = $request->input('tgl_mulai_kontrak') ? Carbon::parse($request->input('tgl_mulai_kontrak')) : null;

        // Jika ada kontrak aktif dan data kontrak berubah, tutup kontrak lama
        if ($detailKontrakAktif && $tglMulaiKontrakBaru) {
            $kontrakBerubah = $detailKontrakAktif->kontrak !== $request->input('kontrak') ||
                              $detailKontrakAktif->tgl_mulai_kontrak->format('Y-m-d') !== $tglMulaiKontrakBaru->format('Y-m-d') ||
                              $detailKontrakAktif->gaji_pokok != ($request->input('gaji_pokok') ?? 0);

            if ($kontrakBerubah) {
                // Tutup kontrak lama dengan set tgl_selesai_kontrak = hari sebelum kontrak baru dimulai
                $detailKontrakAktif->tgl_selesai_kontrak = $tglMulaiKontrakBaru->copy()->subDay();
                $detailKontrakAktif->is_active = false;
                $detailKontrakAktif->save();

                // Buat kontrak baru
                DetailKontrakUser::create([
                    'user_id' => $user->id,
                    'kontrak' => $request->input('kontrak'),
                    'tgl_mulai_kontrak' => $tglMulaiKontrakBaru,
                    'tgl_selesai_kontrak' => $request->input('tgl_selesai_kontrak') ? Carbon::parse($request->input('tgl_selesai_kontrak')) : null,
                    'gaji_pokok' => $request->input('gaji_pokok') ?? 0,
                    'gaji_tunjangan_tetap' => $request->input('gaji_tunjangan_tetap') ?? 0,
                    'gaji_tunjangan_makan' => $request->input('gaji_tunjangan_makan') ?? 0,
                    'gaji_tunjangan_transport' => $request->input('gaji_tunjangan_transport') ?? 0,
                    'gaji_tunjangan_lain' => $request->input('gaji_tunjangan_lain') ?? 0,
                    'gaji_bpjs' => $request->input('gaji_bpjs') ?? 0,
                    'is_active' => true,
                ]);
            } else {
                // Jika tidak ada perubahan, hanya update kontrak aktif
                $detailKontrakAktif->tgl_selesai_kontrak = $request->input('tgl_selesai_kontrak') ? Carbon::parse($request->input('tgl_selesai_kontrak')) : null;
                $detailKontrakAktif->kontrak = $request->input('kontrak');
                $detailKontrakAktif->gaji_pokok = $request->input('gaji_pokok') ?? 0;
                $detailKontrakAktif->gaji_tunjangan_tetap = $request->input('gaji_tunjangan_tetap') ?? 0;
                $detailKontrakAktif->gaji_tunjangan_makan = $request->input('gaji_tunjangan_makan') ?? 0;
                $detailKontrakAktif->gaji_tunjangan_transport = $request->input('gaji_tunjangan_transport') ?? 0;
                $detailKontrakAktif->gaji_tunjangan_lain = $request->input('gaji_tunjangan_lain') ?? 0;
                $detailKontrakAktif->gaji_bpjs = $request->input('gaji_bpjs') ?? 0;
                $detailKontrakAktif->save();
            }
        } elseif (!$detailKontrakAktif && $tglMulaiKontrakBaru) {
            // Jika belum ada kontrak aktif, buat kontrak baru
            DetailKontrakUser::create([
                'user_id' => $user->id,
                'kontrak' => $request->input('kontrak'),
                'tgl_mulai_kontrak' => $tglMulaiKontrakBaru,
                'tgl_selesai_kontrak' => $request->input('tgl_selesai_kontrak') ? Carbon::parse($request->input('tgl_selesai_kontrak')) : null,
                'gaji_pokok' => $request->input('gaji_pokok') ?? 0,
                'gaji_tunjangan_tetap' => $request->input('gaji_tunjangan_tetap') ?? 0,
                'gaji_tunjangan_makan' => $request->input('gaji_tunjangan_makan') ?? 0,
                'gaji_tunjangan_transport' => $request->input('gaji_tunjangan_transport') ?? 0,
                'gaji_tunjangan_lain' => $request->input('gaji_tunjangan_lain') ?? 0,
                'gaji_bpjs' => $request->input('gaji_bpjs') ?? 0,
                'is_active' => true,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
