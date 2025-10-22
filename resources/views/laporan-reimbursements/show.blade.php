<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Reimbursement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ $laporanReimbursement->title }}</h3>
                        <p><strong>User:</strong> {{ $laporanReimbursement->user->name }}</p>
                        <p><strong>Start Date:</strong> {{ $laporanReimbursement->start_date }}</p>
                        <p><strong>End Date:</strong> {{ $laporanReimbursement->end_date }}</p>
                        <p><strong>Total Amount:</strong> {{ number_format($laporanReimbursement->amount, 2) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($laporanReimbursement->status) }}</p>
                        <p><strong>Created At:</strong> {{ $laporanReimbursement->created_at->format('d-m-Y H:i:s') }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.laporan-reimbursements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus::ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
