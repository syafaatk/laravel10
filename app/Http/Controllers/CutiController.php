<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\MasterCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class CutiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $cutis = Cuti::with(['user', 'masterCuti'])->latest()->get();
        } else {
            $cutis = Cuti::where('user_id', $user->id)->with(['user', 'masterCuti'])->latest()->get();
        }
        return view('cuti.index', compact('cutis'));
    }

    public function ajax_index(Request $request)
    {
        $userId = $request->input('user_id');
            
        $cutiDetails = Cuti::where('user_id', $userId)
            ->with('masterCuti')
            ->get();
        
        $output = '';
        if ($cutiDetails->isEmpty()) {
            $output .= '<tr><td colspan="5">No cuti details found for this user.</td></tr>';
        } else {
            foreach ($cutiDetails as $cuti) {
                $output .= '<tr>';
                $output .= '<td>' . ($cuti->masterCuti ? $cuti->masterCuti->name : 'N/A') . '</td>';
                $output .= '<td>' . \Carbon\Carbon::parse($cuti->start_date)->format('d M Y') . '</td>';
                $output .= '<td>' . \Carbon\Carbon::parse($cuti->end_date)->format('d M Y') . '</td>';
                $output .= '<td>' . $cuti->days_requested . '</td>';
                $output .= '<td>' . ucfirst($cuti->status) . '</td>';
                $output .= '</tr>';
            }
        }
        return $output;
    }

    public function create()
    {
        $masterCutis = MasterCuti::all();
        // cek cuti yang sudah diajukan user saat ini dan kurangi dari available days
        // jika admin dapat menambahkan cuti orang lain
        if (Auth::user()->hasRole('admin')) {
            $users = \App\Models\User::all();
            foreach ($masterCutis as $masterCuti) {
                $masterCuti->available_days = $masterCuti->days; // Admin sees total available days
            }
            return view('cuti.create', compact('masterCutis', 'users'));
        }else{
            foreach ($masterCutis as $masterCuti) {
                $usedDays = Cuti::where('user_id', Auth::id())
                    ->where('master_cuti_id', $masterCuti->id)
                    ->where('status', 'approved')
                    ->sum('days_requested');
                $masterCuti->available_days = $masterCuti->days - $usedDays;
            }
            return view('cuti.create', compact('masterCutis'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'master_cuti_id' => 'required|exists:master_cutis,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_requested' => 'nullable|numeric|min:1',
            'reason' => 'required|string|max:1000',
        ]);
        // admin bisa menambahkan cuti orang lain
        if (Auth::user()->hasRole('admin')) {
            $userId = $request->user_id;
            $daysRequested = Carbon::parse($request->end_date)->diffInDays(Carbon::parse($request->start_date)) + 1;
            $masterCuti = MasterCuti::find($request->master_cuti_id);
            if (!$masterCuti) {
                return back()->withErrors(['master_cuti_id' => 'Invalid leave type selected.'])->withInput();
            }
            if ($daysRequested > $masterCuti->days) {
                return back()->withErrors(['end_date' => 'Requested days exceed available days for this leave type.'])->withInput();
            }
            // Cek sisa cuti yang tersedia
            $usedDays = Cuti::where('user_id', $userId)
                ->where('master_cuti_id', $masterCuti->id)
                ->where('status', 'approved')
                ->sum('days_requested');
            $availableDays = $masterCuti->days - $usedDays;
            if ($daysRequested > $availableDays) {
                return back()->withErrors(['end_date' => 'Requested days exceed the remaining leave balance for this type for the selected user.'])->withInput();
            }

            Cuti::create([
                'user_id' => $userId,
                'master_cuti_id' => $request->master_cuti_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'days_requested' => $request->days_requested,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return redirect()->route('cuti.index')->with('success', 'Cuti request submitted successfully for the selected user.');
            
        } else {
            $userId = Auth::id();
            $daysRequested = Carbon::parse($request->end_date)->diffInDays(Carbon::parse($request->start_date)) + 1;
            $masterCuti = MasterCuti::find($request->master_cuti_id);
            if (!$masterCuti) {
                return back()->withErrors(['master_cuti_id' => 'Invalid leave type selected.'])->withInput();
            }
            if ($daysRequested > $masterCuti->days) {
                return back()->withErrors(['end_date' => 'Requested days exceed available days for this leave type.'])->withInput();
            }
            // Cek sisa cuti yang tersedia
            $usedDays = Cuti::where('user_id', Auth::id())
                ->where('master_cuti_id', $masterCuti->id)
                ->where('status', 'approved')
                ->sum('days_requested');
            $availableDays = $masterCuti->days - $usedDays;
            if ($daysRequested > $availableDays) {
                return back()->withErrors(['end_date' => 'Requested days exceed your remaining leave balance for this type.'])->withInput();
            }

            Cuti::create([
                'user_id' => Auth::id(),
                'master_cuti_id' => $request->master_cuti_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'days_requested' => $request->day_requested,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return redirect()->route('cuti.index')->with('success', 'Cuti request submitted successfully.');
        }
        
        
    }

    public function show(Cuti $cuti)
    {
        // Otorisasi: User hanya bisa melihat miliknya, admin bisa melihat semua
        if (Auth::user()->hasRole('admin') || Auth::id() === $cuti->user_id) {
            return view('cuti.show', compact('cuti'));
        }

        abort(403);
    }

    public function approve(Cuti $cuti)
    {
        Gate::authorize('approve-cuti');

        $cuti->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('cuti.index')->with('success', 'Cuti approved.');
    }

    public function reject(Cuti $cuti)
    {
        Gate::authorize('approve-cuti');

        $cuti->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('cuti.index')->with('success', 'Cuti rejected.');
    }

    public function cancel(Cuti $cuti)
    {
        // User hanya bisa membatalkan cuti yang diajukan sendiri dan masih berstatus pending
        if (Auth::id() === $cuti->user_id && $cuti->status === 'pending') {
            $cuti->delete();
            return redirect()->route('cuti.index')->with('success', 'Cuti request cancelled.');
        }

        abort(403);
    }

    public function print(Cuti $cuti)
    {
        // Otorisasi: User hanya bisa melihat miliknya, admin bisa melihat semua
        if (Auth::user()->hasRole('admin') || Auth::id() === $cuti->user_id) {
            $report_data['form_number_month'] = $this->form_number_month(Carbon::now()->month);
            $report_data['cuti'] = $cuti;
            $report_data['master_cuti'] = MasterCuti::find($cuti->master_cuti_id);
            
            // Hanya cuti approved di tahun berjalan
            $currentYear = Carbon::now()->year;
            $report_data['taken_leave_this_year'] = Cuti::where('user_id', $cuti->user_id)
                ->where('master_cuti_id', $cuti->master_cuti_id)
                ->where('status', 'approved')
                ->whereYear('start_date', $currentYear)
                ->sum('days_requested');
            $report_data['remaining_leave'] = $report_data['master_cuti']->days - $report_data['taken_leave_this_year'];
            $jatahCuti = $report_data['master_cuti']->days;
            return view('cuti.print', compact('cuti', 'report_data', 'jatahCuti'));
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