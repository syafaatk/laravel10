<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Cuti Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Details for: {{ $masterCuti->name }}</span>
                    <a href="{{ route('admin.master-cuti.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Name:</strong> {{ $masterCuti->name }}</p>
                            <p><strong>Days:</strong> {{ $masterCuti->days }}</p>
                            <p><strong>Created At:</strong> {{ $masterCuti->created_at->format('d F Y, H:i') }}</p>
                            <p><strong>Updated At:</strong> {{ $masterCuti->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>