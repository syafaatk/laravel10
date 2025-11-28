<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reimbursement;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        if ($user->hasRole('admin')) {
            // Admin Statistics
            $totalUsers = User::count();
            $totalReimbursements = Reimbursement::count();
            $pendingReimbursements = Reimbursement::where('status', 'pending')->count();
            $approvedReimbursements = Reimbursement::where('status', 'approved')->count();
            $doneReimbursements = Reimbursement::where('status', 'done')->count();
            $rejectedReimbursements = Reimbursement::where('status', 'rejected')->count();
            
            $totalCutiRequests = Cuti::count();
            $pendingCutiRequests = Cuti::where('status', 'pending')->count();
            $approvedCutiRequests = Cuti::where('status', 'approved')->count();
            $rejectedCutiRequests = Cuti::where('status', 'rejected')->count();

            // Recent Activities
            $recentReimbursements = Reimbursement::with('user')
                ->latest()
                ->limit(5)
                ->get();

            $recentCuti = Cuti::with(['user', 'masterCuti'])
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard', compact(
                'totalUsers',
                'totalReimbursements',
                'pendingReimbursements',
                'approvedReimbursements',
                'doneReimbursements',
                'rejectedReimbursements',
                'totalCutiRequests',
                'pendingCutiRequests',
                'approvedCutiRequests',
                'rejectedCutiRequests',
                'recentReimbursements',
                'recentCuti',
                'currentYear'
            ));
        }
        
        if ($user->hasRole('user')) {
            // User Statistics
            $totalReimbursements = $user->reimbursements()->count();
            $pendingReimbursements = $user->reimbursements()->where('status', 'pending')->count();
            $approvedReimbursements = $user->reimbursements()->where('status', 'approved')->count();
            $doneReimbursements = $user->reimbursements()->where('status', 'done')->count();
            $rejectedReimbursements = $user->reimbursements()->where('status', 'rejected')->count();

            $totalCutiRequests = $user->cutis()->count();
            $pendingCutiRequests = $user->cutis()->where('status', 'pending')->count();
            $approvedCutiRequests = $user->cutis()->where('status', 'approved')->count();
            $rejectedCutiRequests = $user->cutis()->where('status', 'rejected')->count();

            // My Recent Activities
            $myReimbursements = $user->reimbursements()
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($r) {
                    return [
                        'title' => 'Reimbursement: ' . $r->title,
                        'description' => 'Rp ' . number_format($r->amount, 0, ',', '.') . ' - ' . ucfirst($r->status),
                        'date' => $r->created_at->format('d M Y')
                    ];
                });

            $myCutis = $user->cutis()
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($c) {
                    return [
                        'title' => 'Cuti: ' . ($c->masterCuti->name ?? 'Leave'),
                        'description' => $c->days_requested . ' days - ' . ucfirst($c->status),
                        'date' => $c->created_at->format('d M Y')
                    ];
                });

            $myRecentActivities = collect()
                ->merge($myReimbursements)
                ->merge($myCutis)
                ->sortByDesc('date')
                ->take(10);

            return view('dashboard', compact(
                'totalReimbursements',
                'pendingReimbursements',
                'approvedReimbursements',
                'doneReimbursements',
                'rejectedReimbursements',
                'totalCutiRequests',
                'pendingCutiRequests',
                'approvedCutiRequests',
                'rejectedCutiRequests',
                'myRecentActivities',
                'currentYear'
            ));
        }
    }
}