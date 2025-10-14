<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        return view('admin.payment-settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'payment_mode' => 'required|in:manual,gateway',
            'payment_gateway' => 'required_if:payment_mode,gateway|in:none,midtrans,xendit,doku,ipaymu',
            'payment_enabled' => 'boolean',
            
            // Manual settings
            'manual_account_number' => 'nullable|string|max:255',
            'manual_account_name' => 'nullable|string|max:255',
            'manual_bank_name' => 'nullable|string|max:255',
            'manual_instructions' => 'nullable|string|max:1000',
            'manual_qris_name' => 'nullable|string|max:255',
            'manual_qris_instructions' => 'nullable|string|max:1000',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'remove_current_image' => 'nullable|string',
            
            // Midtrans settings
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_merchant_id' => 'nullable|string|max:255',
            'midtrans_environment' => 'nullable|in:sandbox,production',
            
            // Xendit settings
            'xendit_secret_key' => 'nullable|string|max:255',
            'xendit_public_key' => 'nullable|string|max:255',
            'xendit_environment' => 'nullable|in:sandbox,production',
            
            // DOKU settings
            'doku_mall_id' => 'nullable|string|max:255',
            'doku_shared_key' => 'nullable|string|max:255',
            'doku_environment' => 'nullable|in:sandbox,production',
            
            // iPaymu settings
            'ipaymu_api_key' => 'nullable|string|max:255',
            'ipaymu_va' => 'nullable|string|max:255',
            'ipaymu_environment' => 'nullable|in:sandbox,production',
        ]);

        // Handle QRIS image upload
        $qrisImagePath = Setting::getValue('manual_qris_image', '');
        
        // Check if user wants to remove current image
        if ($request->has('remove_current_image') && $request->remove_current_image === '1') {
            // Delete old file if exists
            if ($qrisImagePath && file_exists(public_path($qrisImagePath))) {
                unlink(public_path($qrisImagePath));
            }
            $qrisImagePath = '';
        }
        
        // Handle new image upload
        if ($request->hasFile('qris_image')) {
            // Delete old file if exists
            if ($qrisImagePath && file_exists(public_path($qrisImagePath))) {
                unlink(public_path($qrisImagePath));
            }
            
            $file = $request->file('qris_image');
            $filename = 'qris-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Save to public/qris folder directly
            $file->move(public_path('qris'), $filename);
            $qrisImagePath = 'qris/' . $filename;
        }

        // Update payment settings
        $settings = [
            'payment_mode' => $request->payment_mode,
            'payment_gateway' => $request->payment_gateway ?? 'none',
            'payment_enabled' => '1', // Always keep payment enabled when settings are saved
            
            // Manual settings - ensure no null values
            'manual_account_number' => $request->manual_account_number ?? '',
            'manual_account_name' => $request->manual_account_name ?? '',
            'manual_bank_name' => $request->manual_bank_name ?? '',
            'manual_instructions' => $request->manual_instructions ?? '',
            'manual_qris_name' => $request->manual_qris_name ?? '',
            'manual_qris_instructions' => $request->manual_qris_instructions ?? '',
            'manual_qris_enabled' => '1', // Always enabled when manual mode is selected
            'manual_qris_image' => $qrisImagePath ?? '',
            
            // Midtrans settings - ensure no null values
            'midtrans_server_key' => $request->midtrans_server_key ?? '',
            'midtrans_client_key' => $request->midtrans_client_key ?? '',
            'midtrans_merchant_id' => $request->midtrans_merchant_id ?? '',
            'midtrans_environment' => $request->midtrans_environment ?? 'sandbox',
            
            // Xendit settings - ensure no null values
            'xendit_secret_key' => $request->xendit_secret_key ?? '',
            'xendit_public_key' => $request->xendit_public_key ?? '',
            'xendit_environment' => $request->xendit_environment ?? 'sandbox',
            
            // DOKU settings - ensure no null values
            'doku_mall_id' => $request->doku_mall_id ?? '',
            'doku_shared_key' => $request->doku_shared_key ?? '',
            'doku_environment' => $request->doku_environment ?? 'sandbox',
            
            // iPaymu settings - ensure no null values
            'ipaymu_api_key' => $request->ipaymu_api_key ?? '',
            'ipaymu_va' => $request->ipaymu_va ?? '',
            'ipaymu_environment' => $request->ipaymu_environment ?? 'sandbox',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.payment-settings')
            ->with('success', 'Pengaturan pembayaran berhasil disimpan.');
    }
}
