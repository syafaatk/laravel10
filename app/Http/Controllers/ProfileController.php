<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->address = $request->input('address');
        $request->user()->no_wa = $request->input('no_wa');
        $request->user()->motor = $request->input('motor');
        $request->user()->ukuran_baju = $request->input('ukuran_baju');
        $request->user()->tgl_masuk = $request->input('tgl_masuk');
        if ($request->hasFile('attachment_ttd')) {
            // Delete old attachment if exists
            if ($request->user()->attachment_ttd) {
                Storage::delete('public/' . $request->user()->attachment_ttd);
            }
            $request->user()->attachment_ttd = $request->file('attachment_ttd')->store('attachments_ttd', 'public');
        }
        $request->user()->nopeg = $request->input('nopeg');
        $request->user()->kontrak = $request->input('kontrak');
        $request->user()->jabatan = $request->input('jabatan');
        

        $request->user()->save();

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
