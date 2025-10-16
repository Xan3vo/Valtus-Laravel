<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RobuxPricingController extends Controller
{
    public function index()
    {
        $settings = [
            'robux_price_per_100' => Setting::getValue('robux_price_per_100', '10000'),
            'gamepass_tax_rate' => Setting::getValue('gamepass_tax_rate', '30'),
            'minimal_purchase' => Setting::getValue('minimal_purchase', '5000'),
            'robux_min_order' => Setting::getValue('robux_min_order', '100'),
            'auto_complete_days' => Setting::getValue('auto_complete_days', '5'),
            'robux_stock' => Setting::getValue('robux_stock', '100000'),
            'robux_stock_minimum' => Setting::getValue('robux_stock_minimum', '10000'),
        ];

        return view('admin.robux-pricing', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'robux_price_per_100' => 'required|numeric|min:0',
            'gamepass_tax_rate' => 'required|numeric|min:0|max:100',
            'robux_min_order' => 'required|integer|min:1',
            'auto_complete_days' => 'required|numeric|min:1|max:30',
            'robux_stock' => 'required|integer|min:0',
            'robux_stock_minimum' => 'required|integer|min:0',
        ]);

        Setting::setValue('robux_price_per_100', $request->robux_price_per_100, 'Price per 100 Robux');
        Setting::setValue('gamepass_tax_rate', $request->gamepass_tax_rate, 'GamePass tax rate percentage');
        Setting::setValue('robux_min_order', $request->robux_min_order, 'Minimal Robux order');
        Setting::setValue('auto_complete_days', $request->auto_complete_days, 'Days to auto-complete orders');
        Setting::setValue('robux_stock', $request->robux_stock, 'Available Robux stock');
        Setting::setValue('robux_stock_minimum', $request->robux_stock_minimum, 'Minimum Robux stock alert');

        return redirect()->back()->with('success', 'Robux pricing updated successfully.');
    }
}
