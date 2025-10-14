<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (Auth::user()->hasRole('admin'))
                    <!-- card -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- card -->
                        <div class="bg-blue-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $totalUsers }}</h2>
                                    <p class="text-lg">Total Users</p>
                                </div>
                            </div>
                        </div> 
                        <!-- card -->
                        <div class="bg-green-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $totalReimbursements }}</h2>
                                    <p class="text-lg">Total Reimbursements</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="bg-red-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $pendingReimbursements }}</h2>
                                    <p class="text-lg">Total Pending Reimbursements</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $totalCutiRequests }}</h2>
                                    <p class="text-lg">Total Cuti</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="bg-gray-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $pendingCutiRequests }}</h2>
                                    <p class="text-lg">Total Pending Cuti</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="p-6 text-gray-900">
                    @if (Auth::user()->hasRole('user'))
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- card -->
                        <div class="bg-blue-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $totalReimbursements }}</h2>
                                    <p class="text-lg">Total Reimbursements</p>
                                </div>
                            </div>
                        </div> 
                        <!-- card -->
                        <div class="bg-green-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $pendingReimbursements }}</h2>
                                    <p class="text-lg">Total Pending Reimbursements</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->
                        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $totalCutiRequests }}</h2>
                                    <p class="text-lg">Total Cuti Requests</p>
                                </div>
                            </div>
                        </div>
                        <!-- card -->   
                        <div class="bg-gray-500 text-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">{{ $pendingCutiRequests }}</h2>
                                    <p class="text-lg">Total Pending Cuti Requests</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
