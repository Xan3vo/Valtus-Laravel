<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class GroupRobuxSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'group_name' => Setting::getValue('group_name', 'Valtus Studios'),
            'group_robux_price_per_100' => Setting::getValue('group_robux_price_per_100', '10000'),
            'group_robux_stock' => Setting::getValue('group_robux_stock', '50000'),
            'group_robux_stock_minimum' => Setting::getValue('group_robux_stock_minimum', '5000'),
            'group_robux_min_order' => Setting::getValue('group_robux_min_order', Setting::getValue('robux_min_order', '100')),
            'robux_min_order' => Setting::getValue('robux_min_order', '100'),
            'group_link' => Setting::getValue('group_link', 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about'),
            'group_id' => Setting::getValue('group_id', '35148970'),
            'min_membership_days' => Setting::getValue('min_membership_days', '14'),
            'processing_hours' => Setting::getValue('processing_hours', '5-7'),
            'robux_group_discount_active' => Setting::getValue('robux_group_discount_active', '0'),
            'robux_group_discount_method' => Setting::getValue('robux_group_discount_method', ''),
            'robux_group_discount_value' => Setting::getValue('robux_group_discount_value', '0'),
            'robux_group_discount_min_amount' => Setting::getValue('robux_group_discount_min_amount', '1000'),
        ];

        return view('admin.group-robux-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'group_robux_price_per_100' => 'required|numeric|min:0',
            'group_robux_stock' => 'required|integer|min:0',
            'group_robux_stock_minimum' => 'required|integer|min:0',
            'group_robux_min_order' => 'required|integer|min:1',
            'group_link' => 'required|url',
            'group_id' => 'required|integer|min:1',
            'min_membership_days' => 'required|integer|min:1|max:365',
            'processing_hours' => 'required|string|max:50',
            'robux_group_discount_active' => 'boolean',
            'robux_group_discount_method' => 'nullable|in:percentage,fixed_amount',
            'robux_group_discount_value' => 'nullable|numeric|min:0',
            'robux_group_discount_min_amount' => 'nullable|integer|min:0',
        ]);

        Setting::setValue('group_name', $request->group_name, 'Group Name for Robux Group Purchase');
        Setting::setValue('group_robux_price_per_100', $request->group_robux_price_per_100, 'Price per 100 Robux via Group');
        Setting::setValue('group_robux_stock', $request->group_robux_stock, 'Available Robux stock for Group method');
        Setting::setValue('group_robux_stock_minimum', $request->group_robux_stock_minimum, 'Minimum Robux stock alert for Group method');
        Setting::setValue('group_robux_min_order', $request->group_robux_min_order, 'Minimum order for Robux via Group');
        Setting::setValue('group_link', $request->group_link, 'Roblox Group URL');
        Setting::setValue('group_id', $request->group_id, 'Roblox Group ID');
        Setting::setValue('min_membership_days', $request->min_membership_days, 'Minimum membership days required');
        Setting::setValue('processing_hours', $request->processing_hours, 'Estimated processing time in hours');
        Setting::setValue('robux_group_discount_active', $request->boolean('robux_group_discount_active') ? '1' : '0', 'Enable discount for Robux Group');
        Setting::setValue('robux_group_discount_method', $request->robux_group_discount_method ?? '', 'Discount method for Robux Group');
        Setting::setValue('robux_group_discount_value', $request->robux_group_discount_value ?? '0', 'Discount value for Robux Group');
        Setting::setValue('robux_group_discount_min_amount', $request->robux_group_discount_min_amount ?? '1000', 'Minimum purchase amount to get discount (in Robux)');

        return redirect()->back()->with('success', 'Group Robux settings updated successfully.');
    }
}


