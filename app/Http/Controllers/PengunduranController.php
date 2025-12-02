<?php

namespace App\Http\Controllers;

use App\Models\Pengunduran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class PengunduranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $pengundurans = Pengunduran::with('user', 'processor')->get();
        } else {
            $pengundurans = Pengunduran::with('user', 'processor')
                ->where('user_id', $user->id)
                ->get();
        }
           
        return view('pengundurans.index', compact('pengundurans')); 
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::all();
        return view('pengundurans.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'requested_date' => 'required|date',
            'reason' => 'required|string|max:2000',
            'pic' => 'nullable|string|max:255',
        ]);

        $pengunduran = Pengunduran::create([
            'user_id' => Auth::id(),
            'requested_date' => $request->requested_date,
            'reason' => $request->reason,
            'pic' => $request->pic,
            'status' => 'pending',
        ]);

        return redirect()->route('pengunduran.index')->with('success', 'Pengunduran diri submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengunduran $pengunduran)
    {
        if (Auth::user()->hasRole('admin') || Auth::id() === $pengunduran->user_id) {
            return view('pengundurans.show', compact('pengunduran'));
        }
        abort(403);
    }

    /**
     * Approve the specified resource.
     */

    public function approve(Request $request, Pengunduran $pengunduran)
    {
        Gate::authorize('approve-pengunduran');

        $pengunduran->status = 'approved';
        $pengunduran->processed_by = Auth::id();
        $pengunduran->save();

        return redirect()->route('pengunduran.index')->with('success', 'Pengunduran diri approved successfully.');
    }

    /**
     * Reject the specified resource.
     */
    public function reject(Request $request, Pengunduran $pengunduran)
    {
        Gate::authorize('approve-pengunduran');

        $pengunduran->status = 'rejected';
        $pengunduran->processed_by = Auth::id();
        $pengunduran->save();

        return redirect()->route('pengunduran.index')->with('success', 'Pengunduran diri rejected successfully.');
    }

    public function destroy(Pengunduran $pengunduran)
    {
        Gate::authorize('delete-pengunduran');

        $pengunduran->delete();

        return redirect()->route('pengunduran.index')->with('success', 'Pengunduran diri deleted successfully.');
    }

    public function print(Pengunduran $pengunduran)
    {
        if (Auth::user()->hasRole('admin') || Auth::id() === $pengunduran->user_id) {
            $report_data = [];
            $report_data['form_number'] = str_pad($pengunduran->id, 3, '0', STR_PAD_LEFT);
            $report_data['form_number_month'] = $this->form_number_month(Carbon::now()->month);
            
            return view('pengundurans.print', compact('pengunduran', 'report_data'));
        }
        abort(403);
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
}