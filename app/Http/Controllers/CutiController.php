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
        
        if (Auth::user()->hasRole('admin')) {
            $users = \App\Models\User::all();
            
            // Admin: Calculate available days untuk setiap user yang dipilih
            foreach ($masterCutis as $masterCuti) {
                // Hitung total digunakan dari semua users
                $totalUsedDays = Cuti::where('master_cuti_id', $masterCuti->id)
                    ->where('status', 'approved')
                    ->sum('days_requested');
                
                // Available days = total - yang sudah dipakai
                $masterCuti->available_days = $masterCuti->days - $totalUsedDays;
            }
            
            // Siapkan data remaining cuti per user untuk reference di form
            $usersRemainingCuti = [];
            foreach ($users as $user) {
                $usersRemainingCuti[$user->id] = [];
                foreach ($masterCutis as $masterCuti) {
                    $usedDays = Cuti::where('user_id', $user->id)
                        ->where('master_cuti_id', $masterCuti->id)
                        ->where('status', 'approved')
                        ->sum('days_requested');
                    
                    $usersRemainingCuti[$user->id][$masterCuti->id] = [
                        'name' => $masterCuti->name,
                        'total' => $masterCuti->days,
                        'used' => $usedDays,
                        'remaining' => $masterCuti->days - $usedDays
                    ];
                }
            }
            
            return view('cuti.create', compact('masterCutis', 'users', 'usersRemainingCuti'));
        } else {
            // User regular: Calculate available days hanya untuk user tersebut
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

        // target user
        $userId = Auth::user()->hasRole('admin') && $request->user_id ? $request->user_id : Auth::id();

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->startOfDay();

        // Build holiday list: prefer model Holiday / PublicHoliday if exists, otherwise use config arrays
        $holidayDates = [];

        if (class_exists(\App\Models\Holiday::class)) {
            $holidayDates = \App\Models\Holiday::pluck('date')->map(fn($d) => Carbon::parse($d)->toDateString())->toArray();
        } elseif (class_exists(\App\Models\PublicHoliday::class)) {
            $holidayDates = \App\Models\PublicHoliday::pluck('date')->map(fn($d) => Carbon::parse($d)->toDateString())->toArray();
        } else {
            $holidayDates = array_map(fn($d) => Carbon::parse($d)->toDateString(), config('holidays.holidays', []));
        }

        // Cuti bersama list from config (optional)
        $cutiBersama = array_map(fn($d) => Carbon::parse($d)->toDateString(), config('holidays.cuti_bersama', []));

        // compute business days excluding weekends, holidays, cuti bersama
        $daysRequested = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // skip saturday/sunday
            if ($date->isWeekend()) {
                continue;
            }
            $ds = $date->toDateString();
            if (in_array($ds, $holidayDates) || in_array($ds, $cutiBersama)) {
                continue;
            }
            $daysRequested++;
        }

        if ($daysRequested <= 0) {
            return back()->withErrors(['start_date' => 'Periode cuti tidak mengandung hari kerja (Senin-Jumat).'])->withInput();
        }

        $masterCuti = MasterCuti::find($request->master_cuti_id);
        if (!$masterCuti) {
            return back()->withErrors(['master_cuti_id' => 'Invalid leave type selected.'])->withInput();
        }

        // If master type has a maximum days (annual/allowance), validate against it
        if ($daysRequested > $masterCuti->days) {
            return back()->withErrors(['end_date' => 'Requested days exceed available days for this leave type.'])->withInput();
        }

        // Check remaining balance (only approved leaves count)
        $usedDays = Cuti::where('user_id', $userId)
            ->where('master_cuti_id', $masterCuti->id)
            ->where('status', 'approved')
            ->sum('days_requested');

        $availableDays = $masterCuti->days - $usedDays;
        if ($daysRequested > $availableDays) {
            return back()->withErrors(['end_date' => 'Requested days exceed the remaining leave balance for this type.'])->withInput();
        }

        Cuti::create([
            'user_id' => $userId,
            'master_cuti_id' => $request->master_cuti_id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days_requested' => $daysRequested,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('cuti.index')->with('success', 'Cuti request submitted successfully.');
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
            $report_data = [];
            $report_data['form_number'] = str_pad($cuti->id, 3, '0', STR_PAD_LEFT);
            $report_data['form_number_month'] = $this->form_number_month(Carbon::now()->month);
            $report_data['cuti'] = $cuti;
            $report_data['master_cuti'] = MasterCuti::find($cuti->master_cuti_id);
            
            // Hanya cuti approved di tahun berjalan
            $currentYear = Carbon::now()->year;
            $cutiYear = Carbon::parse($cuti->start_date)->year;

            // Jika cuti diambil di tahun setelah tahun berjalan, reset jatah ke 12
            if ($cutiYear > $currentYear) {
                $report_data['taken_leave_this_year'] = Cuti::where('user_id', $cuti->user_id)
                    ->where('master_cuti_id', $cuti->master_cuti_id)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $cutiYear)
                    ->sum('days_requested');
                $report_data['remaining_leave'] = $report_data['master_cuti']->days - $report_data['taken_leave_this_year'];
                $jatahCuti = $report_data['master_cuti']->days ?? 12;
            } else {
                $report_data['taken_leave_this_year'] = Cuti::where('user_id', $cuti->user_id)
                    ->where('master_cuti_id', $cuti->master_cuti_id)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days_requested');

                $report_data['remaining_leave'] = $report_data['master_cuti']->days - $report_data['taken_leave_this_year'];
                $jatahCuti = $report_data['master_cuti']->days ?? 12;
            }

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

    // generate cuti report - form filter
    public function search()
    {
        return view('cuti.search');
    }

    public function generate(Request $request)
    {   
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Generate date range array
        $dateRange = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateRange[] = $date->copy();
        }

        // Get all users join kontrak order by kontrak
        $users = \App\Models\User::with('detailKontrakUserActive')
            ->whereHas('detailKontrakUserActive')
            ->get()
            ->sortBy(function($user) {
                return $user->detailKontrakUserActive->kontrak;
            });

        // Get cuti data within the date range
        $cutis = Cuti::where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhereBetween('end_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate->toDateString())
                            ->where('end_date', '>=', $endDate->toDateString());
                      });
            })
            ->where('status', 'approved')
            ->get();

        // Prepare cuti data for easy lookup
        $cutiData = [];
        foreach ($cutis as $cuti) {
            $current = Carbon::parse($cuti->start_date);
            $cutiEnd = Carbon::parse($cuti->end_date);
            while ($current->lte($cutiEnd)) {
                if ($current->gte($startDate) && $current->lte($endDate)) {
                    $cutiData[$cuti->user_id][] = $current->toDateString();
                }
                $current->addDay();
            }
        }

        // get holiday list
        $holidayDates = [];
        if (class_exists(\App\Models\Holiday::class)) {
            $holidayDates = \App\Models\Holiday::pluck('date')->map(fn($d) => Carbon::parse($d)->toDateString())->toArray();
        } elseif (class_exists(\App\Models\PublicHoliday::class)) {
            $holidayDates = \App\Models\PublicHoliday::pluck('date')->map(fn($d) => Carbon::parse($d)->toDateString())->toArray();
        } else {
            $holidayDates = array_map(fn($d) => Carbon::parse($d)->toDateString(), config('holidays.holidays', []));
        }

        return view('cuti.report', compact('startDate', 'endDate', 'dateRange', 'users', 'cutiData', 'holidayDates'));
    }
    
}