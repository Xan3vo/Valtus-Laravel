<?php

namespace App\Http\Controllers;

use App\Models\ReferralClick;
use App\Models\ReferralCode;
use App\Models\ReferralConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ReferralController extends Controller
{
    private const COOKIE_NAME = 'referral_code';
    private const COOKIE_TTL_MINUTES = 60; // 1 hour

    public function handleReferral(Request $request, string $code)
    {
        $referralCode = ReferralCode::where('code', strtoupper(trim($code)))
            ->where('is_active', true)
            ->first();

        if (!$referralCode) {
            return redirect()->route('home');
        }

        ReferralClick::create([
            'referral_code_id' => $referralCode->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'clicked_at' => now(),
        ]);

        session([self::COOKIE_NAME => $referralCode->code]);

        return redirect()->route('home')->withCookie(
            Cookie::make(self::COOKIE_NAME, $referralCode->code, self::COOKIE_TTL_MINUTES)
        );
    }

    public function ownerDashboard(Request $request, string $code, string $secret)
    {
        $referralCode = ReferralCode::where('code', strtoupper(trim($code)))
            ->where('secret_token', $secret)
            ->firstOrFail();

        $conversions = ReferralConversion::where('referral_code_id', $referralCode->id)
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function (ReferralConversion $c) {
                return [
                    'order_id' => $c->order_id,
                    'buyer_username' => $this->maskString($c->buyer_username),
                    'buyer_email' => $this->maskEmail($c->buyer_email),
                    'order_amount' => (float) $c->order_amount,
                    'buyer_discount_amount' => (float) $c->buyer_discount_amount,
                    'reward_amount' => (float) $c->reward_amount,
                    'status' => $c->status,
                    'created_at' => $c->created_at,
                ];
            });

        $approvedTotal = (float) ReferralConversion::where('referral_code_id', $referralCode->id)
            ->where('status', 'approved')
            ->sum('reward_amount');

        return view('referral.dashboard', [
            'referralCode' => $referralCode,
            'approvedTotal' => $approvedTotal,
            'conversions' => $conversions,
            'publicLink' => url('/r/' . $referralCode->code),
        ]);
    }

    private function maskEmail(?string $email): ?string
    {
        if (!$email) {
            return null;
        }

        $email = trim($email);
        $parts = explode('@', $email, 2);
        if (count($parts) !== 2) {
            return $this->maskString($email);
        }

        return $this->maskString($parts[0]) . '@' . $parts[1];
    }

    private function maskString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return '';
        }

        if (mb_strlen($value) <= 3) {
            return mb_substr($value, 0, 1) . str_repeat('*', max(0, mb_strlen($value) - 1));
        }

        return mb_substr($value, 0, 3) . str_repeat('*', 3);
    }
}
