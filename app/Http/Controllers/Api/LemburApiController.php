<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lembur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LemburApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lembur::with(['user', 'approved']);

        // Admin can see all, users only see theirs
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        // Filtering
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $lemburs = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'data' => $lemburs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:weekdays,weekend',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'durasi_jam' => 'required|numeric|min:0.5',
            'keterangan' => 'required|string',
        ]);

        $lembur = new Lembur($validated);
        $lembur->user_id = Auth::id();
        $lembur->status = 'pending';
        $lembur->save();

        return response()->json([
            'message' => 'Pengajuan lembur berhasil dikirim.',
            'data' => $lembur->load(['user'])
        ], 201);
    }

    public function show(Lembur $lembur)
    {
        // Authorization check
        if (!Auth::user()->hasRole('admin') && $lembur->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $lembur->load(['user', 'approved'])
        ]);
    }
}
