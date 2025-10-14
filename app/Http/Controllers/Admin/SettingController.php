<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::getValue('site_name', 'Valtus'),
            'site_description' => Setting::getValue('site_description', 'Top Up Robux Terpercaya'),
            'contact_email' => Setting::getValue('contact_email', ''),
            'whatsapp_number' => Setting::getValue('whatsapp_number', ''),
            'address' => Setting::getValue('address', ''),
            'instagram_username' => Setting::getValue('instagram_username', ''),
            'tiktok_username' => Setting::getValue('tiktok_username', ''),
            'facebook_page' => Setting::getValue('facebook_page', ''),
            'discord_server' => Setting::getValue('discord_server', ''),
            'telegram_username' => Setting::getValue('telegram_username', ''),
            'youtube_channel' => Setting::getValue('youtube_channel', ''),
            'maintenance_mode' => Setting::getValue('maintenance_mode', '0') === '1',
            'maintenance_message' => Setting::getValue('maintenance_message', ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'instagram_username' => 'nullable|string|max:50',
            'tiktok_username' => 'nullable|string|max:50',
            'facebook_page' => 'nullable|url|max:255',
            'discord_server' => 'nullable|url|max:255',
            'telegram_username' => 'nullable|string|max:50',
            'youtube_channel' => 'nullable|url|max:255',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:1000',
        ]);

        // General settings
        Setting::setValue('site_name', $request->site_name, 'Site name');
        Setting::setValue('site_description', $request->site_description, 'Site description');
        Setting::setValue('contact_email', $request->contact_email, 'Contact email');
        Setting::setValue('whatsapp_number', $request->whatsapp_number, 'WhatsApp number');
        Setting::setValue('address', $request->address, 'Address');
        
        // Social media settings
        Setting::setValue('instagram_username', $request->instagram_username, 'Instagram username');
        Setting::setValue('tiktok_username', $request->tiktok_username, 'TikTok username');
        Setting::setValue('facebook_page', $request->facebook_page, 'Facebook page');
        Setting::setValue('discord_server', $request->discord_server, 'Discord server');
        Setting::setValue('telegram_username', $request->telegram_username, 'Telegram username');
        Setting::setValue('youtube_channel', $request->youtube_channel, 'YouTube channel');
        
        // System settings
        Setting::setValue('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0', 'Maintenance mode');
        Setting::setValue('maintenance_message', $request->maintenance_message, 'Maintenance message');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
