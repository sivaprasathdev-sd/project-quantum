@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col">
        <h1 class="fw-bold text-light mb-1">System Dashboard</h1>
        <p class="text-secondary small mb-0">Overview of Quantum inventory metrics and operations logs.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between hover-scale">
            <div>
                <span class="text-secondary small fw-semibold d-block mb-1">Total SKUs</span>
                <h2 class="fw-bold text-light mb-0">{{ $totalSku }}</h2>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-primary-glow" style="width: 60px; height: 60px;">
                <i class="bi bi-box-seam text-primary fs-3"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between hover-scale">
            <div>
                <span class="text-secondary small fw-semibold d-block mb-1">Low Stock Alerts</span>
                <h2 class="fw-bold text-light mb-0 @if($lowStockCount > 0) text-danger @endif">{{ $lowStockCount }}</h2>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-danger-glow" style="width: 60px; height: 60px;">
                <i class="bi bi-exclamation-triangle text-danger fs-3"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between hover-scale">
            <div>
                <span class="text-secondary small fw-semibold d-block mb-1">Pending Purchase Orders</span>
                <h2 class="fw-bold text-light mb-0">{{ $pendingPurchaseOrders }}</h2>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center bg-warning-glow" style="width: 60px; height: 60px;">
                <i class="bi bi-receipt text-warning fs-3"></i>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log Section -->
<div class="glass-card p-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-light mb-1">Recent Audit Log</h3>
            <p class="text-secondary small mb-0">System-wide stock movements and adjustments logs.</p>
        </div>
        <i class="bi bi-clock-history text-secondary fs-4"></i>
    </div>

    <div class="table-responsive">
        @if($activityLogs->isEmpty())
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-database-exclamation fs-1 d-block mb-3 opacity-50"></i>
                <span>No stock movements logged yet.</span>
            </div>
        @else
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product SKU & Name</th>
                        <th>Operator</th>
                        <th>Type</th>
                        <th class="text-center">Quantity Change</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activityLogs as $log)
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
                            <td>
                                @if($log->type === 'in')
                                    <span class="badge badge-soft-success py-1.5 px-2.5">Stock In</span>
                                @elseif($log->type === 'out')
                                    <span class="badge badge-soft-danger py-1.5 px-2.5">Stock Out</span>
                                @else
                                    <span class="badge badge-soft-primary py-1.5 px-2.5">Adjustment</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold">
                                @if($log->quantity_changed > 0)
                                    <span class="text-success">+{{ $log->quantity_changed }}</span>
                                @else
                                    <span class="text-danger">{{ $log->quantity_changed }}</span>
                                @endif
                            </td>
                            <td class="small text-light" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $log->reason }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
