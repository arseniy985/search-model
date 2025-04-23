<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }} - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-info {
            color: #666;
            font-size: 14px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .order-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #666;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                width: 100%;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <div class="company-logo">HyperX Store</div>
            <div class="company-info">
                1234 Gaming Street, E-commerce City<br>
                support@hyperxstore.com | +1 (555) 123-4567
            </div>
        </div>
        
        <div class="order-info">
            <div class="order-title">Order #{{ $order->id }}</div>
            
            <div class="order-details">
                <div class="info-row">
                    <div class="info-label">Order Date:</div>
                    <div>{{ $order->created_at->format('F d, Y h:i A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div>{{ $order->status }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Customer:</div>
                    <div>{{ $order->user->name }} ({{ $order->user->email }})</div>
                </div>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->title }}</td>
                        <td>${{ $item->unit_price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right" style="font-weight: bold;">Order Total:</td>
                    <td class="text-right" style="font-weight: bold;">${{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer">
            <p>Thank you for your order!</p>
            <p>This is a computer-generated document and does not require a signature.</p>
            <p>For any questions, please contact our customer support.</p>
        </div>
        
        <div class="print-button" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()">Print Order</button>
        </div>
    </div>
</body>
</html> 