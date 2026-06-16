@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
    <div>
        <h1 class="fw-bold text-light mb-1">Products Inventory</h1>
        <p class="text-secondary small mb-0">Manage stock quantities, adjust levels, and track product catalog details.</p>
    </div>
    @if(Auth::user()->hasAnyRole(['admin']))
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="bi bi-plus-lg me-1"></i> Add Product
        </button>
    @endif
</div>

<!-- Products Catalog Grid -->
<div class="glass-card p-5">
    <div class="table-responsive">
        @if($products->isEmpty())
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-box-fill fs-1 d-block mb-3 opacity-50"></i>
                <span>No products registered in the inventory yet.</span>
            </div>
        @else
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th class="text-center">Quantity</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        @php
                            $isLowStock = $product->quantity <= $product->min_threshold;
                        @endphp
                        <tr>
                            <td class="fw-bold text-primary small">{{ $product->sku }}</td>
                            <td>
                                <div class="fw-semibold text-light">{{ $product->name }}</div>
                                <div class="text-muted small" style="font-size: 11px;">{{ Str::limit($product->description, 50) }}</div>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary px-2.5 py-1.5">{{ $product->category->name }}</span>
                            </td>
                            <td class="fw-semibold text-light">₹{{ number_format($product->price, 2) }}</td>
                            <td class="text-center fw-bold text-light">
                                {{ $product->quantity }}
                            </td>
                            <td>
                                @if($isLowStock)
                                    <span class="badge bg-danger px-2.5 py-1.5">Low Stock (Limit: {{ $product->min_threshold }})</span>
                                @else
                                    <span class="badge badge-soft-success px-2.5 py-1.5">Healthy</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <!-- Stock adjustment is allowed for Admin, Manager, and Staff -->
                                    <button class="btn btn-secondary-custom py-1.5 px-2.5 small" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#adjustStockModal"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-sku="{{ $product->sku }}"
                                            data-qty="{{ $product->quantity }}">
                                        <i class="bi bi-arrow-down-up me-1"></i> Adjust
                                    </button>

                                    @if(Auth::user()->hasAnyRole(['admin']))
                                        <button class="btn btn-secondary-custom py-1.5 px-2.5 small text-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editProductModal"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-sku="{{ $product->sku }}"
                                                data-category="{{ $product->category_id }}"
                                                data-price="{{ $product->price }}"
                                                data-threshold="{{ $product->min_threshold }}"
                                                data-description="{{ $product->description }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary-custom py-1.5 px-2.5 small text-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@if(Auth::user()->hasAnyRole(['admin']))
<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card p-4 text-light">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold modal-title">Create Product</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="create_category_id" class="form-label text-secondary small fw-semibold">Category</label>
                        <select name="category_id" id="create_category_id" class="form-select form-control-custom" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_sku" class="form-label text-secondary small fw-semibold">SKU Code</label>
                            <input type="text" name="sku" id="create_sku" class="form-control form-control-custom" placeholder="QTY-PROD-01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_name" class="form-label text-secondary small fw-semibold">Product Name</label>
                            <input type="text" name="name" id="create_name" class="form-control form-control-custom" placeholder="Product Name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="create_description" class="form-label text-secondary small fw-semibold">Description</label>
                        <textarea name="description" id="create_description" class="form-control form-control-custom" rows="2" placeholder="Product details..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="create_price" class="form-label text-secondary small fw-semibold">Price (₹)</label>
                            <input type="number" step="0.01" name="price" id="create_price" class="form-control form-control-custom" placeholder="29.99" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="create_quantity" class="form-label text-secondary small fw-semibold">Initial Qty</label>
                            <input type="number" name="quantity" id="create_quantity" class="form-control form-control-custom" placeholder="100" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="create_min_threshold" class="form-label text-secondary small fw-semibold">Alert Limit</label>
                            <input type="number" name="min_threshold" id="create_min_threshold" class="form-control form-control-custom" placeholder="10" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card p-4 text-light">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold modal-title">Edit Product</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label text-secondary small fw-semibold">Category</label>
                        <select name="category_id" id="edit_category_id" class="form-select form-control-custom" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_sku" class="form-label text-secondary small fw-semibold">SKU Code</label>
                            <input type="text" name="sku" id="edit_sku" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label text-secondary small fw-semibold">Product Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-custom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label text-secondary small fw-semibold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control form-control-custom" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_price" class="form-label text-secondary small fw-semibold">Price (₹)</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_min_threshold" class="form-label text-secondary small fw-semibold">Alert Limit</label>
                            <input type="number" name="min_threshold" id="edit_min_threshold" class="form-control form-control-custom" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card p-4 text-light">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold modal-title">Adjust Stock Level</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adjustStockForm" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-4 text-center py-2 px-3 bg-secondary-glow rounded-3">
                        <span class="text-secondary small d-block mb-1" id="adjust_prod_sku"></span>
                        <h5 class="fw-bold text-light mb-1" id="adjust_prod_name"></h5>
                        <div class="small text-muted">Current Quantity: <span class="fw-bold text-primary" id="adjust_prod_qty"></span></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adjust_quantity_changed" class="form-label text-secondary small fw-semibold">Quantity Delta (Positive for In, Negative for Out)</label>
                        <input type="number" name="quantity_changed" id="adjust_quantity_changed" class="form-control form-control-custom" placeholder="e.g. +20 or -5" required>
                    </div>

                    <div class="mb-3">
                        <label for="adjust_reason" class="form-label text-secondary small fw-semibold">Reason / Description</label>
                        <input type="text" name="reason" id="adjust_reason" class="form-control form-control-custom" placeholder="e.g., Received purchase order #12" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Submit Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Edit Product modal setup
        const editProductModalEl = document.getElementById('editProductModal');
        if (editProductModalEl) {
            editProductModalEl.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                
                // Form setup
                document.getElementById('editProductForm').action = `/products/${id}`;
                
                // Fields setup
                document.getElementById('edit_name').value = button.getAttribute('data-name');
                document.getElementById('edit_sku').value = button.getAttribute('data-sku');
                document.getElementById('edit_category_id').value = button.getAttribute('data-category');
                document.getElementById('edit_price').value = button.getAttribute('data-price');
                document.getElementById('edit_min_threshold').value = button.getAttribute('data-threshold');
                document.getElementById('edit_description').value = button.getAttribute('data-description') || '';
            });
        }

        // Handle Adjust Stock modal setup
        const adjustStockModalEl = document.getElementById('adjustStockModal');
        if (adjustStockModalEl) {
            adjustStockModalEl.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                
                // Form setup
                document.getElementById('adjustStockForm').action = `/products/${id}/adjust`;
                
                // Info text setup
                document.getElementById('adjust_prod_sku').innerText = button.getAttribute('data-sku');
                document.getElementById('adjust_prod_name').innerText = button.getAttribute('data-name');
                document.getElementById('adjust_prod_qty').innerText = button.getAttribute('data-qty');
                
                // Clear fields
                document.getElementById('adjust_quantity_changed').value = '';
                document.getElementById('adjust_reason').value = '';
            });
        }
    });
</script>
@endsection
