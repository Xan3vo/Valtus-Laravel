<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Anda Sedang Diproses</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #1f2937;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background-color: #111827; border-radius: 8px; padding: 30px; border: 1px solid #374151;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #10b981; margin: 0; font-size: 24px;">Valtus</h1>
                <p style="color: #9ca3af; margin: 5px 0 0 0; font-size: 12px;">Verified Store</p>
            </div>
            
            <!-- Content -->
            <div style="color: #e5e7eb;">
                <h2 style="color: #ffffff; margin: 0 0 20px 0; font-size: 20px;">🚀 Pesanan Anda Sudah Diproses</h2>
                
                <p style="margin: 0 0 20px 0; line-height: 1.6;">
                    Pesanan Anda sedang diproses oleh tim kami. 
                    @if($order->game_type === 'Robux')
                        Robux akan masuk ke akun Anda dalam waktu yang telah ditentukan.
                    @else
                        Item akan segera masuk ke akun Anda.
                    @endif
                </p>
                
                <!-- Order Details -->
                <div style="background-color: #1f2937; border-radius: 6px; padding: 20px; margin: 20px 0; border: 1px solid #374151;">
                    <h3 style="color: #10b981; margin: 0 0 15px 0; font-size: 16px;">Detail Pesanan</h3>
                    <table style="width: 100%; color: #e5e7eb; font-size: 14px;">
                        <tr>
                            <td style="padding: 8px 0; color: #9ca3af;">Order ID:</td>
                            <td style="padding: 8px 0; text-align: right; font-weight: bold; font-family: monospace;">{{ $order->order_id }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #9ca3af;">Produk:</td>
                            <td style="padding: 8px 0; text-align: right;">
                                @if($order->game_type === 'Robux')
                                    {{ number_format($order->amount, 0, ',', '.') }} Robux
                                    @if($order->purchase_method === 'group')
                                        <span style="color: #a78bfa; font-size: 12px;"> (Via Group)</span>
                                    @else
                                        <span style="color: #10b981; font-size: 12px;"> (Via Gamepass)</span>
                                    @endif
                                @else
                                    {{ $order->product_name ?? $order->game_type }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #9ca3af;">Username:</td>
                            <td style="padding: 8px 0; text-align: right;">{{ $order->username }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #9ca3af;">Total Pembayaran:</td>
                            <td style="padding: 8px 0; text-align: right; color: #10b981; font-weight: bold;">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #9ca3af;">Status:</td>
                            <td style="padding: 8px 0; text-align: right; color: #10b981; font-weight: bold;">Sedang Diproses</td>
                        </tr>
                    </table>
                </div>
                
                <!-- Estimated Completion Time -->
                @if($estimatedCompletion)
                <div style="background-color: #1e3a5f; border-radius: 6px; padding: 15px; margin: 20px 0; border-left: 4px solid #3b82f6;">
                    <p style="margin: 0; line-height: 1.6; color: #93c5fd;">
                        <strong style="color: #ffffff;">⏰ Estimasi Waktu:</strong><br>
                        @if($order->game_type === 'Robux')
                            Robux akan masuk ke akun Anda dalam <strong style="color: #ffffff;">{{ $estimatedCompletion }}</strong>.
                        @else
                            Item akan masuk ke akun Anda dalam <strong style="color: #ffffff;">{{ $estimatedCompletion }}</strong>.
                        @endif
                    </p>
                </div>
                @endif
                
                <p style="margin: 20px 0; line-height: 1.6; color: #9ca3af;">
                    <strong style="color: #e5e7eb;">Catatan Penting:</strong><br>
                    @if($order->game_type === 'Robux')
                        Pastikan Anda sudah bergabung dengan group (jika via Group) atau gamepass Anda masih aktif (jika via Gamepass) agar Robux dapat masuk dengan lancar.
                    @else
                        Pastikan username yang Anda berikan sudah benar agar item dapat masuk ke akun yang tepat.
                    @endif
                </p>
                
                <p style="margin: 20px 0; line-height: 1.6; color: #9ca3af;">
                    Jika dalam waktu yang ditentukan item belum masuk, silakan hubungi customer service kami dengan menyertakan Order ID.
                </p>
                
                <!-- CTA Button -->
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ url('/user/status') }}" style="display: inline-block; background-color: #10b981; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                        Cek Status Pesanan
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #374151; text-align: center; color: #9ca3af; font-size: 12px;">
                <p style="margin: 5px 0;">© {{ date('Y') }} Valtus. All rights reserved.</p>
                <p style="margin: 5px 0;">Jika Anda memiliki pertanyaan, silakan hubungi customer service kami.</p>
            </div>
        </div>
    </div>
</body>
</html>

