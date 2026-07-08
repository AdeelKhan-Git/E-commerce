<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>

<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:40px 0;">

        <tr>

            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 5px 20px rgba(0,0,0,.08);">

                    <tr>
                        <td style="background:linear-gradient(90deg,#00d4ff,#ff007a);padding:30px;text-align:center;">

                            <h1 style="margin:0;color:white;font-size:32px;">
                                HMD STORE
                            </h1>

                            <p style="margin-top:10px;color:white;">
                                Thank you for your purchase!
                            </p>

                        </td>
                    </tr>

                    <tr>

                        <td style="padding:35px;">

                            <h2 style="margin-top:0;color:#222;">
                                Hello {{ $order->user->username }},
                            </h2>

                            <p style="color:#666;font-size:15px;line-height:1.8;">

                                We've received your order and it's now being processed.

                                Thank you for shopping with us.

                            </p>

                            <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

                            <table width="100%" cellpadding="8">

                                <tr>

                                    <td><strong>Order Number</strong></td>

                                    <td>{{ $order->order_number }}</td>

                                </tr>

                                <tr>

                                    <td><strong>Order Date</strong></td>

                                    <td>{{ $order->created_at->format('d M Y h:i A') }}</td>

                                </tr>

                                <tr>

                                    <td><strong>Payment Method</strong></td>

                                    <td>{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</td>

                                </tr>

                                <tr>

                                    <td><strong>Order Status</strong></td>

                                    <td>{{ ucfirst($order->order_status) }}</td>

                                </tr>

                                <tr>

                                    <td><strong>Total</strong></td>

                                    <td>

                                        <strong>

                                            ${{ number_format($order->total_amount, 2) }}

                                        </strong>

                                    </td>

                                </tr>

                            </table>

                            <h3 style="margin-top:35px;color:#222;">
                                Order Items
                            </h3>

                            <table width="100%" cellpadding="10" style="border-collapse:collapse;">

                                <thead>

                                    <tr style="background:#00d4ff;color:white;">
                                        <th align="left">Image</th>
                                        <th align="left">Product</th>
                                        <th align="center">Qty</th>
                                        <th align="right">Price</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($order->items as $item)
                                        <tr>

                                            <td style="border-bottom:1px solid #eee;padding:10px;">

                                                @php
                                                    $image = optional($item->product->primaryImage)->file_url;

                                                @endphp

                                                @if ($image)
                                                    <img src="{{ asset($image) }}" width="70">
                                                    alt="{{ $item->product->product_name }}"
                                                    width="70"
                                                    style="display:block;border-radius:8px;border:1px solid #ddd;"
                                                    >
                                                @else
                                                    <div
                                                        style="
                                                    width:70px;
                                                    height:70px;
                                                    background:#f2f2f2;
                                                    text-align:center;
                                                    line-height:70px;
                                                    color:#999;
                                                    border-radius:8px;
                                                ">
                                                        No Image
                                                    </div>
                                                @endif

                                            </td>

                                            <td style="border-bottom:1px solid #eee;">
                                                {{ $item->product->product_name }}
                                            </td>

                                            <td align="center" style="border-bottom:1px solid #eee;">
                                                {{ $item->quantity }}
                                            </td>

                                            <td align="right" style="border-bottom:1px solid #eee;">
                                                ${{ number_format($item->subtotal, 2) }}
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                            <h3 style="margin-top:35px;">
                                Shipping Address
                            </h3>

                            <p style="color:#666;line-height:1.8;">

                                {{ $order->shipping_address }}

                            </p>

                            <div style="margin-top:40px;padding:20px;background:#f8f8f8;border-radius:10px;">

                                <p style="margin:0;font-size:15px;color:#555;">

                                    If you have any questions regarding your order,

                                    feel free to contact our support team.

                                </p>

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td style="background:#111827;color:#ccc;text-align:center;padding:25px;">

                            <p style="margin:0;">

                                © {{ date('Y') }} HMD STORE

                            </p>

                            <p style="margin-top:10px;font-size:13px;">

                                Thank you for choosing us ❤️

                            </p>

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>

</body>

</html>
