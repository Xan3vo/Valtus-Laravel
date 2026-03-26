<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralCodeController extends Controller
{
    public function index()
    {
        $referralCodes = ReferralCode::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.referral-codes', compact('referralCodes'));
    }

    public function show(ReferralCode $referralCode)
    {
        $stats = [
            'clicks_count' => $referralCode->clicks()->count(),
            'conversions_total_count' => $referralCode->conversions()->count(),
            'conversions_pending_count' => $referralCode->conversions()->where('status', 'pending')->count(),
            'conversions_approved_count' => $referralCode->conversions()->where('status', 'approved')->count(),
            'conversions_rejected_count' => $referralCode->conversions()->where('status', 'rejected')->count(),
            'approved_reward_sum' => (float) ($referralCode->conversions()->where('status', 'approved')->sum('reward_amount') ?? 0),
        ];

        $clicks = $referralCode->clicks()
            ->orderByDesc('clicked_at')
            ->paginate(30, ['*'], 'clicks_page');

        $conversions = $referralCode->conversions()
            ->orderByDesc('created_at')
            ->paginate(30, ['*'], 'conversions_page');

        return view('admin.referral-code-detail', compact('referralCode', 'stats', 'clicks', 'conversions'));
    }

    public function create()
    {
        return view('admin.referral-code-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_discount_method' => 'required|in:percentage,fixed_amount',
            'buyer_discount_value' => 'required|numeric|min:0',
            'reward_method' => 'required|in:percentage,fixed_amount',
            'reward_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0|gte:min_order_amount',
            'is_active' => 'boolean',
            'name' => 'nullable|string|max:255',
            'custom_code' => 'nullable|string|max:50|unique:referral_codes,code',
        ]);

        $code = $request->custom_code;
        if (empty($code)) {
            do {
                $code = strtoupper(Str::random(8));
            } while (ReferralCode::where('code', $code)->exists());
        } else {
            $code = strtoupper(trim($code));
            if (ReferralCode::where('code', $code)->exists()) {
                return back()->withErrors(['custom_code' => 'Kode referral sudah digunakan.'])->withInput();
            }
        }

        $secretToken = Str::random(64);
        while (ReferralCode::where('secret_token', $secretToken)->exists()) {
            $secretToken = Str::random(64);
        }

        ReferralCode::create([
            'code' => $code,
            'name' => $request->name,
            'secret_token' => $secretToken,
            'buyer_discount_method' => $request->buyer_discount_method,
            'buyer_discount_value' => $request->buyer_discount_value,
            'reward_method' => $request->reward_method,
            'reward_value' => $request->reward_value,
            'min_order_amount' => $request->min_order_amount,
            'max_order_amount' => $request->max_order_amount,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.referral-codes')->with('success', 'Referral code berhasil dibuat: ' . $code);
    }

    public function edit(ReferralCode $referralCode)
    {
        return view('admin.referral-code-form', compact('referralCode'));
    }

    public function update(Request $request, ReferralCode $referralCode)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:referral_codes,code,' . $referralCode->id,
            'buyer_discount_method' => 'required|in:percentage,fixed_amount',
            'buyer_discount_value' => 'required|numeric|min:0',
            'reward_method' => 'required|in:percentage,fixed_amount',
            'reward_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0|gte:min_order_amount',
            'is_active' => 'boolean',
            'name' => 'nullable|string|max:255',
        ]);

        $referralCode->update([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'buyer_discount_method' => $request->buyer_discount_method,
            'buyer_discount_value' => $request->buyer_discount_value,
            'reward_method' => $request->reward_method,
            'reward_value' => $request->reward_value,
            'min_order_amount' => $request->min_order_amount,
            'max_order_amount' => $request->max_order_amount,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.referral-codes')->with('success', 'Referral code berhasil diperbarui.');
    }

    public function destroy(ReferralCode $referralCode)
    {
        $referralCode->delete();
        return redirect()->route('admin.referral-codes')->with('success', 'Referral code berhasil dihapus.');
    }

    public function toggleStatus(ReferralCode $referralCode)
    {
        $referralCode->update(['is_active' => !$referralCode->is_active]);
        $status = $referralCode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Referral code {$status}.");
    }
}
