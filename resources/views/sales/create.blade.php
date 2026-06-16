@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="d-flex flex-column mb-5">
            <h1 class="fw-bold text-light mb-1">Record Stock Sale</h1>
            <p class="text-secondary small mb-0">Select an item, input the quantity, and record a new transaction.</p>
        </div>

        <div class="glass-card p-5">
            <h3 class="fw-bold text-light mb-4">Sale Details</h3>
            <p class="text-secondary mb-4 small">Ensure the customer transaction details are logged correctly.</p>

            <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                @csrf
                
                <!-- Product Selection -->
                <div class="mb-4">
                    <label for="product_id" class="form-label text-secondary small fw-semibold">Select Product</label>
                    <select name="product_id" id="product_id" class="form-select form-control-custom" required>
                        <option value="">-- Choose Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-qty="{{ $product->quantity }}"
                                    data-price="{{ $product->price }}"
                                    data-sku="{{ $product->sku }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (SKU: {{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Interactive Info Panel -->
                <div id="productInfoPanel" class="mb-4 p-3 bg-secondary-glow rounded-3 d-none">
                    <div class="row g-2">
                        <div class="col-6">
                            <span class="text-secondary small d-block">Available Stock</span>
                            <span class="fw-bold text-primary fs-5" id="infoStock">0</span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary small d-block">Unit Price</span>
                            <span class="fw-bold text-light fs-5" id="infoPrice">₹0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Quantity to Sell -->
                <div class="mb-4">
                    <label for="quantity" class="form-label text-secondary small fw-semibold">Quantity to Sell</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}"
                           class="form-control form-control-custom @error('quantity') is-invalid @enderror" 
                           placeholder="Enter quantity" required>
                    <div class="invalid-feedback" id="qtyFeedback"></div>
                </div>

                <!-- Reason / Description -->
                <div class="mb-4">
                    <label for="reason" class="form-label text-secondary small fw-semibold">Reason / Reference (Optional)</label>
                    <input type="text" name="reason" id="reason" value="{{ old('reason') }}"
                           class="form-control form-control-custom" 
                           placeholder="e.g. Sale Invoice #1023">
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-2" id="submitBtn">
                    <i class="bi bi-cart-dash me-1"></i> Record Sale
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const infoPanel = document.getElementById('productInfoPanel');
        const infoStock = document.getElementById('infoStock');
        const infoPrice = document.getElementById('infoPrice');
        const quantityInput = document.getElementById('quantity');
        const submitBtn = document.getElementById('submitBtn');
        const qtyFeedback = document.getElementById('qtyFeedback');

        function updatePanel() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const stock = parseInt(selectedOption.getAttribute('data-qty'));
                const price = parseFloat(selectedOption.getAttribute('data-price')).toFixed(2);
                
                infoStock.textContent = stock;
                infoPrice.textContent = `₹${price}`;
                infoPanel.classList.remove('d-none');
                
                // Set max quantity dynamically
                quantityInput.max = stock;
            } else {
                infoPanel.classList.add('d-none');
                quantityInput.removeAttribute('max');
            }
            validateQuantity();
        }

        function validateQuantity() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const stock = parseInt(selectedOption.getAttribute('data-qty'));
                const enteredQty = parseInt(quantityInput.value) || 0;

                if (enteredQty > stock) {
                    quantityInput.classList.add('is-invalid');
                    qtyFeedback.textContent = `Insufficient inventory. Available: ${stock}`;
                    submitBtn.disabled = true;
                } else if (enteredQty <= 0 && quantityInput.value !== '') {
                    quantityInput.classList.add('is-invalid');
                    qtyFeedback.textContent = 'Quantity must be at least 1.';
                    submitBtn.disabled = true;
                } else {
                    quantityInput.classList.remove('is-invalid');
                    qtyFeedback.textContent = '';
                    submitBtn.disabled = false;
                }
            } else {
                quantityInput.classList.remove('is-invalid');
                submitBtn.disabled = false;
            }
        }

        productSelect.addEventListener('change', updatePanel);
        quantityInput.addEventListener('input', validateQuantity);
        
        // Initial setup for old input values
        if (productSelect.value) {
            updatePanel();
        }
    });
</script>
@endsection
