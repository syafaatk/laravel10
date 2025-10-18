<?php

namespace App\Http\Controllers;

use App\Models\MasterAsset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterAssetController extends Controller
{
    public function index()
    {
        $assets = MasterAsset::with('assignedToUser')->get();
        $users = User::all();
        return view('admin.master-assets.index', compact('assets', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.master-assets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|unique:assets,serial_number|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'status' => 'required|string|in:available,assigned,maintenance,retired',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        // serial number auto
        if (empty($data['serial_number'])) {
            $latestAsset = MasterAsset::latest()->first();
            $lastId = $latestAsset ? $latestAsset->id : 0;
            $data['serial_number'] = 'SN-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        }
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assets', 'public');
            $data['image'] = $imagePath;
        }

        MasterAsset::create($data);

        return redirect()->route('admin.master-assets.index')->with('success', 'Asset created successfully.');
    }

    public function show(MasterAsset $masterAsset)
    {
        return view('admin.master-assets.show', compact('masterAsset'));
    }

    public function edit(MasterAsset $masterAsset)
    {
        $users = User::all();
        return view('admin.master-assets.edit', compact('masterAsset', 'users'));
    }

    public function update(Request $request, MasterAsset $masterAsset)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,' . $masterAsset->id,
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'status' => 'required|string|in:available,assigned,maintenance,retired',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($masterAsset->image) {
                Storage::disk('public')->delete($masterAsset->image);
            }
            $imagePath = $request->file('image')->store('assets', 'public');
            $data['image'] = $imagePath;
        }

        $masterAsset->update($data);

        return redirect()->route('admin.master-assets.index')->with('success', 'Asset updated successfully.');
        
    }

    public function destroy(MasterAsset $masterAsset)
    {
        if ($masterAsset->image) {
            Storage::disk('public')->delete($masterAsset->image);
        }
        $masterAsset->delete();
        return redirect()->route('admin.master-assets.index')->with('success', 'Asset deleted successfully.');
    }
}
