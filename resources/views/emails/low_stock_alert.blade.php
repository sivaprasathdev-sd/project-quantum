<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #ef4444;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px 20px;
        }
        .product-card {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #ef4444;
        }
        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 10px;
            color: #1e293b;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
        }
        .detail-value {
            color: #0f172a;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Low Stock Warning</h1>
        </div>
        <div class="content">
            <p>Hello Management,</p>
            <p>This is an automated system notification from Project Quantum. The stock for the following product has fallen below its minimum threshold.</p>
            
            <div class="product-card">
                <div class="product-title">{{ $product->name }}</div>
                <div class="detail-row">
                    <span class="detail-label">SKU:</span>
                    <span class="detail-value">{{ $product->sku }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Current Quantity:</span>
                    <span class="detail-value" style="color: #ef4444; font-weight: bold;">{{ $product->quantity }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Minimum Threshold:</span>
                    <span class="detail-value">{{ $product->min_threshold }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Price:</span>
                    <span class="detail-value">${{ number_format($product->price, 2) }}</span>
                </div>
            </div>
            
            <p>Please review and initiate restock orders immediately to prevent fulfillment delays.</p>
        </div>
        <div class="footer">
            <p>Project Quantum - Enterprise Inventory System</p>
        </div>
    </div>
</body>
</html>
