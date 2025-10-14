<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $users = User::all();
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
        return view('admin.users.edit', compact('user', 'roles'));
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
            'nopeg' => 'nullable|integer|unique:users,nopeg,' . $user->id,
        ]);

        $user->fill($request->only([
            'name', 'email', 'address', 'no_wa', 'motor', 'ukuran_baju', 'tgl_masuk', 'nopeg'
        ]));

        if ($request->hasFile('attachment_ttd')) {
            // Delete old attachment if exists
            if ($user->attachment_ttd) {
                Storage::delete('public/' . $user->attachment_ttd);
            }
            $user->attachment_ttd = $request->file('attachment_ttd')->store('attachments_ttd', 'public');
        }

        $user->syncRoles($request->roles);

        $user->save();
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