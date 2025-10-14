<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        // Get current media settings
        $mediaSettings = [
            'home_hero_image' => Setting::getValue('home_hero_image', ''),
            'home_hero_image_type' => Setting::getValue('home_hero_image_type', 'file'), // 'file' or 'url'
            'home_hero_image_url' => Setting::getValue('home_hero_image_url', ''),
            'cara_beli_video' => Setting::getValue('cara_beli_video', ''),
            'cara_beli_video_type' => Setting::getValue('cara_beli_video_type', 'file'), // 'file' or 'url'
            'cara_beli_video_url' => Setting::getValue('cara_beli_video_url', ''),
            'cara_bikin_gamepass_video' => Setting::getValue('cara_bikin_gamepass_video', ''),
            'cara_bikin_gamepass_video_type' => Setting::getValue('cara_bikin_gamepass_video_type', 'file'), // 'file' or 'url'
            'cara_bikin_gamepass_video_url' => Setting::getValue('cara_bikin_gamepass_video_url', ''),
        ];

        return view('admin.media', compact('mediaSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'home_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200', // 50MB
            'home_hero_image_type' => 'required|in:file,url',
            'home_hero_image_url' => 'nullable|url',
            'cara_beli_video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB
            'cara_beli_video_type' => 'required|in:file,url',
            'cara_beli_video_url' => 'nullable|url',
            'cara_bikin_gamepass_video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB
            'cara_bikin_gamepass_video_type' => 'required|in:file,url',
            'cara_bikin_gamepass_video_url' => 'nullable|url',
        ]);

        $settings = [];

        // Handle Home Hero Image
        if ($request->home_hero_image_type === 'file' && $request->hasFile('home_hero_image')) {
            // Delete old file if exists
            $oldImage = Setting::getValue('home_hero_image', '');
            if ($oldImage && file_exists(public_path($oldImage))) {
                unlink(public_path($oldImage));
            }
            
            $file = $request->file('home_hero_image');
            $filename = 'home-hero-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('media'), $filename);
            $settings['home_hero_image'] = 'media/' . $filename;
            $settings['home_hero_image_type'] = 'file';
            $settings['home_hero_image_url'] = '';
        } elseif ($request->home_hero_image_type === 'url' && $request->home_hero_image_url) {
            // Clear file if switching to URL
            $oldImage = Setting::getValue('home_hero_image', '');
            if ($oldImage && file_exists(public_path($oldImage))) {
                unlink(public_path($oldImage));
            }
            $settings['home_hero_image'] = '';
            $settings['home_hero_image_type'] = 'url';
            $settings['home_hero_image_url'] = $request->home_hero_image_url;
        } else {
            // Keep existing values if no changes
            $settings['home_hero_image'] = Setting::getValue('home_hero_image', '');
            $settings['home_hero_image_type'] = $request->home_hero_image_type;
            $settings['home_hero_image_url'] = $request->home_hero_image_url;
        }

        // Handle Cara Beli Video
        if ($request->cara_beli_video_type === 'file' && $request->hasFile('cara_beli_video')) {
            // Delete old file if exists
            $oldVideo = Setting::getValue('cara_beli_video', '');
            if ($oldVideo && file_exists(public_path($oldVideo))) {
                unlink(public_path($oldVideo));
            }
            
            $file = $request->file('cara_beli_video');
            $filename = 'cara-beli-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('media'), $filename);
            $settings['cara_beli_video'] = 'media/' . $filename;
            $settings['cara_beli_video_type'] = 'file';
            $settings['cara_beli_video_url'] = '';
        } elseif ($request->cara_beli_video_type === 'url' && $request->cara_beli_video_url) {
            // Clear file if switching to URL
            $oldVideo = Setting::getValue('cara_beli_video', '');
            if ($oldVideo && file_exists(public_path($oldVideo))) {
                unlink(public_path($oldVideo));
            }
            $settings['cara_beli_video'] = '';
            $settings['cara_beli_video_type'] = 'url';
            $settings['cara_beli_video_url'] = $request->cara_beli_video_url;
        } else {
            // Keep existing values if no changes
            $settings['cara_beli_video'] = Setting::getValue('cara_beli_video', '');
            $settings['cara_beli_video_type'] = $request->cara_beli_video_type;
            $settings['cara_beli_video_url'] = $request->cara_beli_video_url;
        }

        // Handle Cara Bikin Gamepass Video
        if ($request->cara_bikin_gamepass_video_type === 'file' && $request->hasFile('cara_bikin_gamepass_video')) {
            // Delete old file if exists
            $oldVideo = Setting::getValue('cara_bikin_gamepass_video', '');
            if ($oldVideo && file_exists(public_path($oldVideo))) {
                unlink(public_path($oldVideo));
            }
            
            $file = $request->file('cara_bikin_gamepass_video');
            $filename = 'cara-gamepass-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('media'), $filename);
            $settings['cara_bikin_gamepass_video'] = 'media/' . $filename;
            $settings['cara_bikin_gamepass_video_type'] = 'file';
            $settings['cara_bikin_gamepass_video_url'] = '';
        } elseif ($request->cara_bikin_gamepass_video_type === 'url' && $request->cara_bikin_gamepass_video_url) {
            // Clear file if switching to URL
            $oldVideo = Setting::getValue('cara_bikin_gamepass_video', '');
            if ($oldVideo && file_exists(public_path($oldVideo))) {
                unlink(public_path($oldVideo));
            }
            $settings['cara_bikin_gamepass_video'] = '';
            $settings['cara_bikin_gamepass_video_type'] = 'url';
            $settings['cara_bikin_gamepass_video_url'] = $request->cara_bikin_gamepass_video_url;
        } else {
            // Keep existing values if no changes
            $settings['cara_bikin_gamepass_video'] = Setting::getValue('cara_bikin_gamepass_video', '');
            $settings['cara_bikin_gamepass_video_type'] = $request->cara_bikin_gamepass_video_type;
            $settings['cara_bikin_gamepass_video_url'] = $request->cara_bikin_gamepass_video_url;
        }

        // Update settings
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $message = 'Media settings updated successfully!';
        if ($request->hasFile('home_hero_image') || $request->hasFile('cara_beli_video') || $request->hasFile('cara_bikin_gamepass_video')) {
            $message = 'Media uploaded and settings updated successfully!';
        }

        return redirect()->route('admin.media')->with('success', $message);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'type' => 'required|in:home_hero_image,cara_beli_video,cara_bikin_gamepass_video'
        ]);

        $type = $request->type;
        
        // Delete file if exists
        $filePath = Setting::getValue($type, '');
        if ($filePath && file_exists(public_path($filePath))) {
            unlink(public_path($filePath));
        }

        // Clear settings
        Setting::updateOrCreate(['key' => $type], ['value' => '']);
        Setting::updateOrCreate(['key' => $type . '_type'], ['value' => 'file']);
        Setting::updateOrCreate(['key' => $type . '_url'], ['value' => '']);

        return response()->json(['success' => true]);
    }
}
