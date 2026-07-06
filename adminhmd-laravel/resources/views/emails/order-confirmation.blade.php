<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:30px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;">

    <tr>
        <td style="background:#0d0d0d;padding:30px;text-align:center;">
            <h1 style="color:#00f5ff;margin:0;font-size:24px;">✓ Order Confirmed!</h1>
        </td>
    </tr>
    <tr>
        <td style="padding:30px;">
            <p style="font-size:16px;color:#333;">Hi <strong>{{ $order->user->username }}</strong>,</p>
            <p style="color:#555;">Thank you for your order! We've received it and it's now being processed.</p>

            {{-- Product list with images --}}
            <table width="100%" style="border-collapse:collapse;margin:20px 0;">
                @foreach($order->items as $item)
                @php
                    $primaryImg = $item->product?->primaryImage;
                    $imgUrl = $primaryImg ? asset($primaryImg->file_url) : null;
                @endphp
                
                <pre>
               
                </pre>
                <tr>
                    <td style="padding:12px 0;border-bottom:1px solid #eee;width:70px;">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}" width="60" height="60" alt="{{ $item->product->product_name }}"
                                 style="border-radius:8px;object-fit:cover;display:block;border:1px solid #eee;">
                        @else
                            <div style="width:60px;height:60px;background:#f0f0f0;border-radius:8px;"></div>
                        @endif
                    </td>
                    <td style="padding:12px 15px;border-bottom:1px solid #eee;vertical-align:middle;">
                        <strong style="color:#333;">{{ $item->product->product_name ?? 'Product' }}</strong><br>
                        <span style="color:#999;font-size:13px;">Qty: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</span>
                    </td>
                    <td style="padding:12px 0;border-bottom:1px solid #eee;text-align:right;vertical-align:middle;white-space:nowrap;">
                        <strong>${{ number_format($item->subtotal, 2) }}</strong>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="padding:15px 0 0;text-align:right;font-weight:bold;font-size:16px;">Order Total:</td>
                    <td style="padding:15px 0 0;text-align:right;font-weight:bold;font-size:16px;color:#00a8b3;">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>

            <table width="100%" style="background:#f9f9f9;border-radius:4px;padding:15px;margin:20px 0;border-collapse:collapse;">
                <tr><td style="padding:5px;"><strong>Order Number:</strong></td><td style="padding:5px;color:#ff006e;font-weight:bold;">{{ $order->order_number }}</td></tr>
                <tr><td style="padding:5px;"><strong>Payment Method:</strong></td><td style="padding:5px;">{{ ucwords(str_replace('_',' ', $order->payment_method ?? '—')) }}</td></tr>
                <tr><td style="padding:5px;"><strong>Order Status:</strong></td><td style="padding:5px;"><span style="background:#fff3cd;color:#856404;padding:2px 8px;border-radius:4px;">{{ ucfirst($order->order_status) }}</span></td></tr>
            </table>

            <p style="color:#555;">You'll receive another email when your order status changes.</p>
        </td>
    </tr>

    <tr>
        <td style="background:#f4f4f4;padding:20px;text-align:center;">
            <p style="color:#999;font-size:12px;margin:0;">&copy; {{ date('Y') }} HMDStore. All rights reserved.</p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>