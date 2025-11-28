<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            
            {{-- STATISTICS CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-6 mb-6 ml-4 mr-4">
                @if (Auth::user()->hasRole('admin'))
                    <!-- Total Users -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-semibold">Total Users</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $totalUsers }}</h2>
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-10-5v5m5-5v5m-9 .75h14"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Reimbursements -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-semibold">Total Reimburse</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $totalReimbursements }}</h2>
                            </div>
                            <div class="bg-green-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8.5 10.5A1.5 1.5 0 1010 9a1.5 1.5 0 00-1.5 1.5z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Reimbursements -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-semibold">Pending Reimburse</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $pendingReimbursements }}</h2>
                            </div>
                            <div class="bg-orange-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 6h2v5H9V6zm0 6h2v2H9v-2z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Cuti -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-semibold">Total Cuti Requests</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $totalCutiRequests }}</h2>
                            </div>
                            <div class="bg-purple-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Cuti -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-semibold">Pending Cuti</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $pendingCutiRequests }}</h2>
                            </div>
                            <div class="bg-red-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Reimbursements -->
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-100 text-sm font-semibold">Approved Reimburse</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $approvedReimbursements ?? 0 }}</h2>
                            </div>
                            <div class="bg-indigo-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- User Dashboard Cards -->
                    <!-- My Reimbursements -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-semibold">My Reimburse</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $totalReimbursements }}</h2>
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8.5 10.5A1.5 1.5 0 1010 9a1.5 1.5 0 00-1.5 1.5z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Reimbursements -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-semibold">Pending Requests</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $pendingReimbursements }}</h2>
                            </div>
                            <div class="bg-orange-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 6h2v5H9V6zm0 6h2v2H9v-2z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Reimbursements -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-semibold">Approved Requests</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $approvedReimbursements ?? 0 }}</h2>
                            </div>
                            <div class="bg-green-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- My Cuti Requests -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-semibold">My Cuti Requests</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $totalCutiRequests }}</h2>
                            </div>
                            <div class="bg-purple-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Cuti -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-semibold">Pending Cuti</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $pendingCutiRequests }}</h2>
                            </div>
                            <div class="bg-red-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Remaining Cuti Days -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-semibold">Remaining Cuti Days</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $remainingCutiDays }} / 12</h2>
                            </div>
                            <div class="bg-yellow-400 bg-opacity-30 rounded-full p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"></path></svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- CHARTS & RECENT ACTIVITY --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                <!-- Status Overview Chart -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reimbursement Status Overview</h3>
                    <canvas id="statusChart"></canvas>
                </div>

                <!-- Cuti Status Chart -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Cuti Status Overview</h3>
                    <canvas id="cutiChart"></canvas>
                </div>

                <!-- only admin table sisa cuti semua pegawai dalam bentuk table -->
                @if (Auth::user()->hasRole('admin'))
                <div class="bg-white rounded-lg shadow-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Remaining Cuti Days by Employee</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse" id="remainingCutiTable">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left font-semibold text-gray-800 sticky left-0 bg-gray-100">Employee Name</th>
                                    @foreach ($remainingCutiDays as $cutiType)
                                        <th class="border border-gray-300 px-4 py-2 text-center font-semibold text-gray-800">{{ $cutiType['name'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employeesRemainingCuti as $employeeName => $cutiData)    
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-300 px-4 py-2 text-gray-700 sticky left-0 bg-white">{{ $employeeName }}</td>
                                        @foreach ($cutiData as $cuti)
                                            <td class="border border-gray-300 px-4 py-2 text-center text-gray-800">
                                                <!-- tambahkan warna badge berdasarkan remaining_days -->
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                                    @if($cuti['remaining_days'] == 0) bg-red-100 text-red-800
                                                    @elseif($cuti['remaining_days'] <= 3) bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ $cuti['remaining_days'] }} days
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                {{-- USER RECENT ACTIVITIES --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">My Recent Activities</h3>
                    <div class="space-y-3">
                        @forelse ($myRecentActivities ?? [] as $activity)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $activity['title'] ?? 'Activity' }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity['description'] ?? '' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $activity['date'] ?? now()->format('d M Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent activities</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>    

            {{-- RECENT ACTIVITIES --}}
            @if (Auth::user()->hasRole('admin'))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Reimbursements -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Reimbursements</h3>
                    <div class="space-y-3">
                        @forelse ($recentReimbursements ?? [] as $reimbursement)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $reimbursement->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $reimbursement->title }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-800">Rp {{ number_format($reimbursement->amount, 0, ',', '.') }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                        @if($reimbursement->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($reimbursement->status == 'approved') bg-green-100 text-green-800
                                        @elseif($reimbursement->status == 'done') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($reimbursement->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent reimbursements</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Cuti Requests -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Cuti Requests</h3>
                    <div class="space-y-3">
                        @forelse ($recentCuti ?? [] as $cuti)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $cuti->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $cuti->masterCuti->name }} • {{ $cuti->days_requested }} days</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $cuti->start_date->format('d M Y') }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                        @if($cuti->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($cuti->status == 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($cuti->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No recent cuti requests</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

            {{-- REIMBURSEMENT BY MONTH TABLE --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Transportation Reimbursement by Month</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left font-semibold text-gray-800 sticky left-0 bg-gray-100">Employee Name</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center font-semibold text-gray-800">October</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center font-semibold text-gray-800">November</th>
                                    <th class="border border-gray-300 px-4 py-2 text-center font-semibold text-gray-800">December</th>
                                    <th class="border border-gray-300 px-4 py-2 text-right font-semibold text-gray-800">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentYear = \Carbon\Carbon::now()->year;
                                    $employees = \App\Models\User::with(['reimbursements' => function($q) use ($currentYear) {
                                        $q->whereYear('created_at', $currentYear)
                                        ->where('tipe', '1')
                                        ->where('status', 'approved');
                                    }])->get();
                                @endphp

                                @forelse ($employees as $employee)
                                    @php
                                        $monthlyTotal = [];
                                        $yearTotal = 0;
                                        
                                        for ($month = 10; $month <= 12; $month++) {
                                            $monthTotal = $employee->reimbursements
                                                ->filter(fn($r) => \Carbon\Carbon::parse($r->created_at)->month === $month)
                                                ->sum('amount');
                                            $monthlyTotal[$month] = $monthTotal;
                                            $yearTotal += $monthTotal;
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                                        <td class="border border-gray-300 px-4 py-2 font-medium text-gray-800 sticky left-0 bg-white">
                                            {{ $employee->name }}
                                        </td>
                                        @for ($month = 10; $month <= 12; $month++)
                                            <td class="border border-gray-300 px-4 py-2 text-center text-gray-700">
                                                @if ($monthlyTotal[$month] > 0)
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">
                                                        Rp{{ number_format($monthlyTotal[$month], 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="border border-gray-300 px-4 py-2 text-right font-bold text-gray-900 bg-blue-50">
                                            Rp{{ number_format($yearTotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="border border-gray-300 px-4 py-4 text-center text-gray-500">
                                            No transportation reimbursement data available for this year.
                                        </td>
                                    </tr>
                                @endforelse

                                {{-- TOTAL ROW --}}
                                @php
                                    $monthlyGrandTotal = [];
                                    $grandTotal = 0;
                                    for ($month = 10; $month <= 12; $month++) {
                                        $monthSum = $employees->sum(function($emp) use ($month) {
                                            return $emp->reimbursements
                                                ->filter(fn($r) => \Carbon\Carbon::parse($r->created_at)->month === $month)
                                                ->sum('amount');
                                        });
                                        $monthlyGrandTotal[$month] = $monthSum;
                                        $grandTotal += $monthSum;
                                    }
                                @endphp
                                <tr class="bg-gray-200 font-bold text-gray-900">
                                    <td class="border border-gray-300 px-4 py-2 sticky left-0 bg-gray-200">TOTAL</td>
                                    @for ($month = 10; $month <= 12; $month++)
                                        <td class="border border-gray-300 px-4 py-2 text-center">
                                            Rp{{ number_format($monthlyGrandTotal[$month], 0, ',', '.') }}
                                        </td>
                                    @endfor
                                    <td class="border border-gray-300 px-4 py-2 text-right bg-blue-200">
                                        Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-xs text-gray-600">
                        <p>📊 <strong>Year:</strong> {{ $currentYear }}</p>
                        <p>📝 <strong>Filter:</strong> Only approved transportation reimbursements are included.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- datatable remainingCutiTable -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#remainingCutiTable').DataTable({
                "paging":   true,
                "ordering": true,
                "info":     false,
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 5,
                "lengthChange": false,
            });
        });
    </script>

    <script>
        // Reimbursement Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Done', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $pendingReimbursements ?? 0 }},
                            {{ $approvedReimbursements ?? 0 }},
                            {{ $doneReimbursements ?? 0 }},
                            {{ $rejectedReimbursements ?? 0 }}
                        ],
                        backgroundColor: ['#FBBF24', '#34D399', '#60A5FA', '#F87171'],
                        borderColor: ['#FCD34D', '#6EE7B7', '#93C5FD', '#FCA5A5'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        // Cuti Status Chart
        const cutiCtx = document.getElementById('cutiChart');
        if (cutiCtx) {
            new Chart(cutiCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $pendingCutiRequests ?? 0 }},
                            {{ $approvedCutiRequests ?? 0 }},
                            {{ $rejectedCutiRequests ?? 0 }}
                        ],
                        backgroundColor: ['#FBBF24', '#34D399', '#F87171'],
                        borderColor: ['#FCD34D', '#6EE7B7', '#FCA5A5'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>