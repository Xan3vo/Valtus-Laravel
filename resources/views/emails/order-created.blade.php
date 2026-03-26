<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Anda Sudah Dibuat</title>
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
                <h2 style="color: #ffffff; margin: 0 0 20px 0; font-size: 20px;">Pesanan Anda Sudah Dibuat</h2>
                
                <p style="margin: 0 0 20px 0; line-height: 1.6;">
                    Terima kasih! Pesanan Anda telah berhasil dibuat. Kami sedang menunggu konfirmasi dari admin untuk melanjutkan proses.
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
                            <td style="padding: 8px 0; text-align: right; color: #fbbf24;">Menunggu Konfirmasi Admin</td>
                        </tr>
                    </table>
                </div>
                
                <p style="margin: 20px 0; line-height: 1.6; color: #9ca3af;">
                    <strong style="color: #e5e7eb;">Langkah Selanjutnya:</strong><br>
                    Tim kami akan segera memeriksa bukti transfer Anda. Proses konfirmasi biasanya memakan waktu 3-5 jam (di luar jam kerja bisa lebih lama).
                </p>
                
                <p style="margin: 20px 0; line-height: 1.6; color: #9ca3af;">
                    Anda dapat mengecek status pesanan kapan saja di halaman status pesanan.
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


