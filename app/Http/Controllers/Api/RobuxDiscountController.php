<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Models\RobuxDiscountRule;
use Illuminate\Http\Request;

class RobuxDiscountController extends Controller
{
    private function getActiveReferralCode(Request $request): ?ReferralCode
    {
        $code = $request->cookie('referral_code') ?: session('referral_code');
        if (!$code) {
            return null;
        }

        $code = strtoupper(trim((string) $code));
        if ($code === '') {
            return null;
        }

        return ReferralCode::where('code', $code)
            ->where('is_active', true)
            ->first();
    }

    private function calculateDiscountAmount(string $method, float $value, float $price): float
    {
        if ($price <= 0) {
            return 0;
        }

        if ($method === 'percentage') {
            $discount = $price * ($value / 100);
        } else {
            $discount = $value;
        }

        return (float) min($discount, $price);
    }

    /**
     * Get discount information for given amount and purchase method
     */
    public function getDiscount(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'purchase_method' => 'required|in:gamepass,group',
        ]);

        $amount = (int) $request->amount;
        $purchaseMethod = $request->purchase_method;

        // Calculate base price (from settings) - always calculate this first
        $pricePer100 = (float) \App\Models\Setting::getValue(
            $purchaseMethod === 'gamepass' ? 'robux_price_per_100' : 'group_robux_price_per_100',
            '10000'
        );
        $basePrice = $pricePer100 * ($amount / 100);
        
        // Find matching discount rule
        $rule = RobuxDiscountRule::findMatchingRule($amount, $purchaseMethod);

        $ruleDiscountAmount = 0;
        $priceAfterRule = $basePrice;
        if ($rule) {
            $ruleDiscountAmount = $rule->calculateDiscount($basePrice);
            $priceAfterRule = max(0, $basePrice - $ruleDiscountAmount);
        }

        // Apply referral discount (preview only; actual price is validated again on createOrder)
        $referralCode = $this->getActiveReferralCode($request);
        $referralApplied = false;
        $referralDiscountAmount = 0;
        $priceAfterReferral = $priceAfterRule;
        if ($referralCode && $referralCode->isValidForOrderAmount((float) $priceAfterRule)) {
            $referralDiscountAmount = $this->calculateDiscountAmount(
                (string) $referralCode->buyer_discount_method,
                (float) $referralCode->buyer_discount_value,
                (float) $priceAfterRule
            );
            $priceAfterReferral = max(0, (float) $priceAfterRule - (float) $referralDiscountAmount);
            $referralApplied = $referralDiscountAmount > 0;
        }

        $totalDiscountAmount = (float) max(0, $ruleDiscountAmount + $referralDiscountAmount);
        $finalPrice = (float) $priceAfterReferral;
        $hasDiscount = $totalDiscountAmount > 0;

        $discountPercentage = $basePrice > 0 ? ($totalDiscountAmount / $basePrice) * 100 : 0;
        
        return response()->json([
            // Backward compatible fields
            'has_discount' => $hasDiscount,
            'discount_amount' => round($totalDiscountAmount, 2),
            'discount_percentage' => round($discountPercentage, 2),
            'base_price' => round($basePrice, 2),
            'final_price' => round($finalPrice, 2),

            // Rule info
            'rule_id' => $rule?->id,
            'rule_description' => $rule?->description,
            'discount_method' => $rule?->discount_method,
            'discount_value' => $rule ? (float) $rule->discount_value : null,
            'rule_discount_amount' => round((float) $ruleDiscountAmount, 2),

            // Referral info
            'referral_active' => (bool) $referralCode,
            'referral_applied' => (bool) $referralApplied,
            'referral_code' => $referralCode?->code,
            'referral_discount_amount' => round((float) $referralDiscountAmount, 2),
        ]);
    }
}



