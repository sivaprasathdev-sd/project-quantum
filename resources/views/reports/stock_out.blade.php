@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
    <div>
        <h1 class="fw-bold text-light mb-1">Stock Out Report</h1>
        <p class="text-secondary small mb-0">Audit history of all negative stock adjustments and outgoing inventory logs.</p>
    </div>
</div>

<!-- Search Bar & Statistics Summary -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="glass-card p-4">
            <form action="{{ route('reports.stock-out') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" value="{{ $search }}" 
                       class="form-control form-control-custom" 
                       placeholder="Search by Product Name or SKU...">
                @if($search)
                    <a href="{{ route('reports.stock-out') }}" class="btn btn-secondary-custom d-flex align-items-center">Clear</a>
                @endif
                <button type="submit" class="btn btn-primary-custom d-flex align-items-center gap-2">
                    <i class="bi bi-search"></i> Search
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-secondary small fw-semibold d-block mb-1">Total Records</span>
                <h4 class="fw-bold text-light mb-0">{{ $logs->total() }} entries</h4>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-danger-glow" style="width: 50px; height: 50px;">
                <i class="bi bi-arrow-up-right-square text-danger fs-4"></i>
            </div>
        </div>
    </div>
</div>

<!-- Logs Table -->
<div class="glass-card p-5">
    <div class="table-responsive">
        @if($logs->isEmpty())
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-database-exclamation fs-1 d-block mb-3 opacity-50"></i>
                <span>No outgoing stock logs found.</span>
            </div>
        @else
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product SKU & Name</th>
                        <th>Operator</th>
                        <th class="text-center">Quantity Removed</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td class="text-secondary small" style="white-space: nowrap;">
                                {{ $log->created_at }}
                            </td>
                            <td>
                                <div class="fw-semibold text-light">{{ $log->product->name ?? 'Deleted Product' }}</div>
                                <div class="text-muted small" style="font-size: 11px;">{{ $log->product->sku ?? 'N/A' }}</div>
                            </td>
                            <td class="small text-secondary">
                                <i class="bi bi-person-circle me-1"></i> {{ $log->user->name ?? 'System' }}
                            </td>
                            <td class="text-center fw-bold text-danger">
                                -{{ abs($log->quantity_changed) }}
                            </td>
                            <td class="small text-light" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $log->reason }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {!! $logs->appends(['search' => $search])->links('pagination::bootstrap-5') !!}
            </div>
        @endif
    </div>
</div>
@endsection
