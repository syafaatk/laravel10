<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DetailKontrakUser;
use App\Models\Cuti;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view-user');
        $users = User::withSum('cutiApproved', 'days_requested')->get();
           
        return view('admin.users.index', compact('users')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $detailKontrakAktif = $user->detailKontrakUserActive;
        $detailKontrakHistory = $user->detailKontrakUsers()->orderByDesc('created_at')->get();
        
        return view('admin.users.edit', compact('user', 'roles', 'detailKontrakAktif', 'detailKontrakHistory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|array',
            'address' => 'nullable|string|max:255',
            'no_wa' => 'nullable|string|max:255',
            'motor' => 'nullable|string|max:255',
            'ukuran_baju' => 'nullable|string|max:255',
            'tgl_masuk' => 'nullable|date',
            'attachment_ttd' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'attachment_foto_profile' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nopeg' => 'nullable|integer|unique:users,nopeg,' . $user->id,
            'kontrak' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'norek' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'tgl_mulai_kontrak' => 'nullable|date',
            'tgl_selesai_kontrak' => 'nullable|date|after:tgl_mulai_kontrak',
            'gaji_tunjangan_tetap' => 'nullable|integer|min:0',
            'gaji_tunjangan_makan' => 'nullable|integer|min:0',
            'gaji_tunjangan_transport' => 'nullable|integer|min:0',
            'gaji_tunjangan_lain' => 'nullable|integer|min:0',
            'gaji_pokok' => 'nullable|integer|min:0',
            'gaji_bpjs' => 'nullable|integer|min:0',
        ]);

        // Update user basic info
        $user->fill($request->only([
            'name', 'email', 'address', 'no_wa', 'motor', 'ukuran_baju', 'tgl_masuk', 'nopeg', 'jabatan', 'norek', 'bank'
        ]));

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

        $user->syncRoles($request->roles);
        $user->save();

        // Logic untuk update kontrak
        $detailKontrakAktif = $user->detailKontrakUserActive;
        $tglMulaiKontrakBaru = \Carbon\Carbon::parse($request->input('tgl_mulai_kontrak'));

        // Jika ada kontrak aktif dan data kontrak berubah, tutup kontrak lama
        if ($detailKontrakAktif) {
            $kontrakBerubah = $detailKontrakAktif->kontrak !== $request->input('kontrak') ||
                              \Carbon\Carbon::parse($detailKontrakAktif->tgl_mulai_kontrak)->format('Y-m-d') !== $tglMulaiKontrakBaru->format('Y-m-d') ||
                              $detailKontrakAktif->gaji_pokok != ($request->input('gaji_pokok') ?? 0);

            if ($kontrakBerubah) {
                // Tutup kontrak lama dengan tanggal sebelum kontrak baru dimulai
                $detailKontrakAktif->update([
                    'tgl_selesai_kontrak' => $tglMulaiKontrakBaru->copy()->subDay(),
                    'is_active' => false,
                ]);

                // Buat kontrak baru
                DetailKontrakUser::create([
                    'user_id' => $user->id,
                    'kontrak' => $request->input('kontrak'),
                    'tgl_mulai_kontrak' => $tglMulaiKontrakBaru,
                    'tgl_selesai_kontrak' => $request->input('tgl_selesai_kontrak'),
                    'gaji_pokok' => $request->input('gaji_pokok') ?? 0,
                    'gaji_tunjangan_tetap' => $request->input('gaji_tunjangan_tetap') ?? 0,
                    'gaji_tunjangan_makan' => $request->input('gaji_tunjangan_makan') ?? 0,
                    'gaji_tunjangan_transport' => $request->input('gaji_tunjangan_transport') ?? 0,
                    'gaji_tunjangan_lain' => $request->input('gaji_tunjangan_lain') ?? 0,
                    'gaji_bpjs' => $request->input('gaji_bpjs') ?? 0,
                    'is_active' => true,
                ]);

                return redirect()->route('admin.users.edit', $user->id)
                    ->with('success', 'User updated successfully. New contract created and previous contract closed.');
            } else {
                // Update kontrak yang ada jika tidak ada perubahan signifikan
                $detailKontrakAktif->update([
                    'kontrak' => $request->input('kontrak'),
                    'tgl_mulai_kontrak' => $tglMulaiKontrakBaru,
                    'tgl_selesai_kontrak' => $request->input('tgl_selesai_kontrak'),
                    'gaji_pokok' => $request->input('gaji_pokok') ?? 0,
                    'gaji_tunjangan_tetap' => $request->input('gaji_tunjangan_tetap') ?? 0,
                    'gaji_tunjangan_makan' => $request->input('gaji_tunjangan_makan') ?? 0,
                    'gaji_tunjangan_transport' => $request->input('gaji_tunjangan_transport') ?? 0,
                    'gaji_tunjangan_lain' => $request->input('gaji_tunjangan_lain') ?? 0,
                    'gaji_bpjs' => $request->input('gaji_bpjs') ?? 0,
                ]);
            }
        } else {
            // Buat kontrak baru jika belum ada
            DetailKontrakUser::create([
                'user_id' => $user->id,
                'kontrak' => $request->input('kontrak'),
                'tgl_mulai_kontrak' => $tglMulaiKontrakBaru,
                'tgl_selesai_kontrak' => $request->input('tgl_selesai_kontrak'),
                'gaji_pokok' => $request->input('gaji_pokok') ?? 0,
                'gaji_tunjangan_tetap' => $request->input('gaji_tunjangan_tetap') ?? 0,
                'gaji_tunjangan_makan' => $request->input('gaji_tunjangan_makan') ?? 0,
                'gaji_tunjangan_transport' => $request->input('gaji_tunjangan_transport') ?? 0,
                'gaji_tunjangan_lain' => $request->input('gaji_tunjangan_lain') ?? 0,
                'gaji_bpjs' => $request->input('gaji_bpjs') ?? 0,
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}