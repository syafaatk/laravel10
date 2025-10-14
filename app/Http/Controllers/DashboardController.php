<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reimbursement;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $totalUsers = User::count();
            $totalReimbursements = Reimbursement::count();
            $pendingReimbursements = Reimbursement::where('status', 'pending')->count();
            $totalCutiRequests = Cuti::count();
            $pendingCutiRequests = Cuti::where('status', 'pending')->count();

            return view('dashboard', compact(
                'totalUsers',
                'totalReimbursements',
                'pendingReimbursements',
                'totalCutiRequests',
                'pendingCutiRequests'
            ));
        }
        
        if($user->hasRole('user')) {
            $totalReimbursements = $user->reimbursements()->count();
            $pendingReimbursements = $user->reimbursements()->where('status', 'pending')->count();
            $approvedReimbursements = $user->reimbursements()->where('status', 'approved')->count();
            $rejectedReimbursements = $user->reimbursements()->where('status', 'rejected')->count();

            $totalCutiRequests = $user->cutis()->count();
            $pendingCutiRequests = $user->cutis()->where('status', 'pending')->count();
            $approvedCutiRequests = $user->cutis()->where('status', 'approved')->count();
            $rejectedCutiRequests = $user->cutis()->where('status', 'rejected')->count();

            return view('dashboard', compact(
                'totalReimbursements',
                'pendingReimbursements',
                'approvedReimbursements',
                'rejectedReimbursements',
                'totalCutiRequests',
                'pendingCutiRequests',
                'approvedCutiRequests',
                'rejectedCutiRequests'
            ));
        }
    }
}