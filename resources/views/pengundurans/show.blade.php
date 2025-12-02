<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Resignation Request Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header">
                    <span>Resignation Request #{{ $pengunduran->id }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <strong>User:</strong> {{ $pengunduran->user->name }}
                    </div>
                    <div class="mb-4">
                        <strong>Request Date:</strong> {{ $pengunduran->requested_date->format('d M Y') }}
                    </div>
                    <div class="mb-4">
                        <strong>Reason:</strong> {{ $pengunduran->reason }}
                    </div>
                    <div class="mb-4">
                        <strong>PIC:</strong> {{ $pengunduran->pic }}
                    </div>
                    <div class="mb-4">
                        <strong>Status:</strong>
                        <span class="badge {{
                            $pengunduran->status == 'pending' ? 'text-bg-warning' :
                            ($pengunduran->status == 'approved' ? 'text-bg-success' : 'text-bg-danger')
                        }}">
                            {{ ucfirst($pengunduran->status) }}
                        </span>
                    </div>
                    @if ($pengunduran->processed_by)
                        <div class="mb-4">
                            <strong>Processed By:</strong> {{ $pengunduran->processor->name }}
                        </div>
                        <div class="mb-4">
                            <strong>Processed At:</strong> {{ $pengunduran->processed_at ? $pengunduran->processed_at->format('d M Y H:i') : 'N/A' }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('pengunduran.index') }}" class="btn btn-secondary">Back to List</a>
                        @if (Auth::user()->hasRole('admin') && $pengunduran->status == 'pending')
                            <form action="{{ route('pengunduran.approve', $pengunduran) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            <form action="{{ route('pengunduran.reject', $pengunduran) }}" method="POST" class="d-inline">
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
