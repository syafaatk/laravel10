<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Request Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Leave Request #{{ $cuti->id }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <strong>User:</strong> {{ $cuti->user->name }}
                    </div>
                    <div class="mb-4">
                        <strong>Leave Type:</strong> {{ $cuti->masterCuti->name }}
                    </div>
                    <div class="mb-4">
                        <strong>Start Date:</strong> {{ $cuti->start_date->format('d M Y') }}
                    </div>
                    <div class="mb-4">
                        <strong>End Date:</strong> {{ $cuti->end_date->format('d M Y') }}
                    </div>
                    <div class="mb-4">
                        <strong>Days Requested:</strong> {{ $cuti->days_requested }}
                    </div>
                    <div class="mb-4">
                        <strong>Reason:</strong> {{ $cuti->reason }}
                    </div>
                    <div class="mb-4">
                        <strong>Status:</strong>
                        <span class="badge {{
                            $cuti->status == 'pending' ? 'text-bg-warning' :
                            ($cuti->status == 'approved' ? 'text-bg-success' : 'text-bg-danger')
                        }}">
                            {{ ucfirst($cuti->status) }}
                        </span>
                    </div>
                    @if ($cuti->processed_by)
                        <div class="mb-4">
                            <strong>Processed By:</strong> {{ $cuti->processor->name }}
                        </div>
                        <div class="mb-4">
                            <strong>Processed At:</strong> {{ $cuti->processed_at->format('d M Y H:i') }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('cuti.index') }}" class="btn btn-secondary">Back to List</a>
                        @if (Auth::user()->hasRole('admin') && $cuti->status == 'pending')
                            <form action="{{ route('cuti.approve', $cuti) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            <form action="{{ route('cuti.reject', $cuti) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
