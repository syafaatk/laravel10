<?php

namespace App\Http\Controllers;

use App\Models\MasterCuti;
use Illuminate\Http\Request;

class MasterCutiController extends Controller
{
    public function index()
    {
        $masterCutis = MasterCuti::all();
        return view('admin.master-cuti.index', compact('masterCutis'));
    }

    public function create()
    {
        return view('admin.master-cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:master_cutis,name',
            'days' => 'required|integer|min:1',
        ]);

        MasterCuti::create($request->all());

        return redirect()->route('admin.master-cuti.index')->with('success', 'Leave type created successfully.');
    }

    public function edit(MasterCuti $masterCuti)
    {
        return view('admin.master-cuti.edit', compact('masterCuti'));
    }

    public function update(Request $request, MasterCuti $masterCuti)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:master_cutis,name,' . $masterCuti->id,
            'days' => 'required|integer|min:1',
        ]);

        $masterCuti->update($request->all());

        return redirect()->route('admin.master-cuti.index')->with('success', 'Leave type updated successfully.');
    }

    public function destroy(MasterCuti $masterCuti)
    {
        $masterCuti->delete();
        return redirect()->route('admin.master-cuti.index')->with('success', 'Leave type deleted successfully.');
    }
}