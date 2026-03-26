<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $settings = [
            'announcement_enabled' => Setting::getValue('announcement_enabled', '0') === '1',
            'announcement_link' => Setting::getValue('announcement_link', ''),
            'announcement_bar_enabled' => Setting::getValue('announcement_bar_enabled', '0') === '1',
            'announcement_bar_text' => Setting::getValue('announcement_bar_text', ''),
            'announcement_bar_link' => Setting::getValue('announcement_bar_link', ''),
        ];

        return view('admin.announcement', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'announcement_enabled' => 'boolean',
            'announcement_link' => 'nullable|url|max:255',
            'announcement_bar_enabled' => 'boolean',
            'announcement_bar_text' => 'nullable|string|max:255',
            'announcement_bar_link' => 'nullable|url|max:255',
        ]);

        Setting::setValue('announcement_enabled', $request->boolean('announcement_enabled') ? '1' : '0', 'Announcement enabled');
        Setting::setValue('announcement_link', $request->announcement_link, 'Announcement redirect link');

        Setting::setValue('announcement_bar_enabled', $request->boolean('announcement_bar_enabled') ? '1' : '0', 'Announcement bar enabled');
        Setting::setValue('announcement_bar_text', $request->announcement_bar_text, 'Announcement bar text');
        Setting::setValue('announcement_bar_link', $request->announcement_bar_link, 'Announcement bar link');

        return redirect()->back()->with('success', 'Announcement updated successfully.');
    }
}
