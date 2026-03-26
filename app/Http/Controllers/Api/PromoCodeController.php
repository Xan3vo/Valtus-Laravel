<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * Validate and apply promo code
     */
    public function validate(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:50',
                'amount' => 'required|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid. Pastikan kode promo dan jumlah harga diisi dengan benar.',
                'errors' => $e->errors(),
            ], 200);
        }

        $code = strtoupper(trim($request->code));
        $price = (float) ($request->amount ?? $request->price ?? 0); // Support both 'amount' and 'price' for backward compatibility

        // Find promo code
        $promoCode = PromoCode::where('code', $code)->first();

        if (!$promoCode) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ], 200); // Return 200 to avoid CORS/error handling issues
        }

        // Validate promo code
        if (!$promoCode->isValid()) {
            if (!$promoCode->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode promo tidak aktif.',
                ], 200);
            }

            if ($promoCode->current_uses >= $promoCode->max_uses) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode promo sudah habis digunakan.',
                ], 200);
            }
        }

        // Calculate discount (random between min and max)
        $discountValue = $promoCode->getRandomDiscountValue();
        
        if ($promoCode->discount_method === 'percentage') {
            $discountAmount = $price * ($discountValue / 100);
        } else { // fixed_amount
            $discountAmount = min($discountValue, $price);
        }

        $discountAmount = round($discountAmount, 2);
        $finalPrice = max(0, $price - $discountAmount);

        return response()->json([
            'success' => true,
            'promo_code_id' => $promoCode->id,
            'code' => $promoCode->code,
            'discount_method' => $promoCode->discount_method,
            'discount_value_applied' => round($discountValue, 2),
            'discount_amount' => $discountAmount,
            'original_price' => round($price, 2),
            'final_price' => round($finalPrice, 2),
            'message' => 'Kode promo berhasil diterapkan!',
        ]);
    }
}
