<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::getValue('site_name', 'Valtus'),
            'site_description' => Setting::getValue('site_description', 'Top Up Robux Terpercaya'),
            'contact_email' => Setting::getValue('contact_email', ''),
            'whatsapp_number' => Setting::getValue('whatsapp_number', ''),
            'group_robux_min_order' => Setting::getValue('group_robux_min_order', Setting::getValue('robux_min_order', '100')),
            'address' => Setting::getValue('address', ''),
            'instagram_username' => Setting::getValue('instagram_username', ''),
            'tiktok_username' => Setting::getValue('tiktok_username', ''),
            'facebook_page' => Setting::getValue('facebook_page', ''),
            'discord_server' => Setting::getValue('discord_server', ''),
            'telegram_username' => Setting::getValue('telegram_username', ''),
            'youtube_channel' => Setting::getValue('youtube_channel', ''),
            'spreadsheet_url' => Setting::getValue('spreadsheet_url', ''),
            'spreadsheet_script_url' => Setting::getValue('spreadsheet_script_url', ''),
            'spreadsheet_enabled' => Setting::getValue('spreadsheet_enabled', '0') === '1',
            'maintenance_mode' => Setting::getValue('maintenance_mode', '0') === '1',
            'maintenance_message' => Setting::getValue('maintenance_message', ''),
            // Email Configuration (pakai database saja, bukan .env)
            'mail_mailer' => Setting::getValue('mail_mailer', 'log'),
            'mail_host' => Setting::getValue('mail_host', ''),
            'mail_port' => Setting::getValue('mail_port', '587'),
            'mail_username' => Setting::getValue('mail_username', ''),
            'mail_password' => Setting::getValue('mail_password', ''), // Don't show actual password for security
            'mail_encryption' => Setting::getValue('mail_encryption', 'tls'),
            'mail_from_address' => Setting::getValue('mail_from_address', 'hello@example.com'),
            'mail_from_name' => Setting::getValue('mail_from_name', 'Valtus'),
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
            'spreadsheet_url' => 'nullable|url|max:500',
            'spreadsheet_script_url' => 'nullable|url|max:500',
            'spreadsheet_enabled' => 'boolean',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:1000',
            // Email Configuration (Gmail)
            'gmail_email' => 'required|email|max:255',
            'gmail_app_password' => 'nullable|string|max:255', // Optional - jika kosong, pakai yang lama
            'mail_from_name' => 'required|string|max:255',
            'mail_port' => 'required|in:587,465',
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
        
        // Spreadsheet settings
        Setting::setValue('spreadsheet_url', $request->spreadsheet_url, 'Spreadsheet URL');
        Setting::setValue('spreadsheet_script_url', $request->spreadsheet_script_url, 'Spreadsheet Script URL');
        Setting::setValue('spreadsheet_enabled', $request->boolean('spreadsheet_enabled') ? '1' : '0', 'Spreadsheet integration');
        
        // System settings
        Setting::setValue('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0', 'Maintenance mode');
        Setting::setValue('maintenance_message', $request->maintenance_message, 'Maintenance message');
        
        // Email Configuration (Gmail)
        $mailPort = $request->mail_port ?? '587'; // Default 587, bisa pilih 465
        $mailEncryption = ($mailPort == '465') ? 'ssl' : 'tls'; // 465 = SSL, 587 = TLS
        
        Setting::setValue('mail_mailer', 'smtp', 'Mail driver');
        Setting::setValue('mail_host', 'smtp.gmail.com', 'Mail host');
        Setting::setValue('mail_port', $mailPort, 'Mail port');
        Setting::setValue('mail_username', $request->gmail_email, 'Mail username');
        Setting::setValue('mail_from_address', $request->gmail_email, 'Mail from address');
        Setting::setValue('mail_encryption', $mailEncryption, 'Mail encryption');
        
        // Hanya update password jika ada input baru (tidak kosong)
        // Jika kosong, biarkan password lama tetap digunakan
        if (!empty($request->gmail_app_password)) {
            Setting::setValue('mail_password', $request->gmail_app_password, 'Mail password');
        }
        
        Setting::setValue('mail_from_name', $request->mail_from_name, 'Mail from name');
        
        // Apply email config dynamically
        $this->applyEmailConfig();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function downloadScript()
    {
        // Use the main Apps Script file with all features (Email, Deduplication, etc.)
        $scriptPath = base_path('google-apps-script.js');
        
        if (!file_exists($scriptPath)) {
            abort(404, 'Script file not found');
        }

        // Read file content directly to ensure we get the latest version
        $scriptContent = file_get_contents($scriptPath);
        
        return response($scriptContent, 200, [
            'Content-Type' => 'application/javascript; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="valtus-google-apps-script.js"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function viewScript()
    {
        // Use the main Apps Script file with all features (Email, Deduplication, etc.)
        $scriptPath = base_path('google-apps-script.js');
        
        if (!file_exists($scriptPath)) {
            Log::error('Google Apps Script file not found', ['path' => $scriptPath]);
            abort(404, 'Script file not found: ' . $scriptPath);
        }

        // Read file content directly to avoid caching issues
        $scriptContent = file_get_contents($scriptPath);
        
        // Verify file contains expected content (Email column, deduplication, etc.)
        $hasEmail = strpos($scriptContent, "'Email'") !== false || strpos($scriptContent, '"Email"') !== false;
        $hasDeduplication = strpos($scriptContent, 'checkDuplicate') !== false;
        $hasExpectedHeaders = strpos($scriptContent, 'expectedHeaders') !== false;
        
        Log::info('Serving Google Apps Script', [
            'path' => $scriptPath,
            'file_size' => strlen($scriptContent),
            'has_email' => $hasEmail,
            'has_deduplication' => $hasDeduplication,
            'has_expected_headers' => $hasExpectedHeaders,
        ]);
        
        return response($scriptContent, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Script-Version' => '2.0-with-email', // Version header untuk debugging
        ]);
    }
    
    /**
     * Apply email configuration from database settings to config
     */
    private function applyEmailConfig()
    {
        config([
            'mail.default' => Setting::getValue('mail_mailer', 'log'),
            'mail.mailers.smtp.host' => Setting::getValue('mail_host', ''),
            'mail.mailers.smtp.port' => Setting::getValue('mail_port', '587'),
            'mail.mailers.smtp.username' => Setting::getValue('mail_username', ''),
            'mail.mailers.smtp.password' => Setting::getValue('mail_password', ''),
            'mail.mailers.smtp.encryption' => $this->normalizeEncryption(Setting::getValue('mail_encryption', 'tls')),
            'mail.mailers.smtp.timeout' => 60, // 60 seconds timeout
            'mail.from.address' => Setting::getValue('mail_from_address', 'hello@example.com'),
            'mail.from.name' => Setting::getValue('mail_from_name', 'Valtus'),
        ]);
    }
    
    /**
     * Normalize encryption value (handle 'null' string)
     */
    private function normalizeEncryption($encryption)
    {
        return ($encryption === 'null' || $encryption === null) ? null : $encryption;
    }
}
