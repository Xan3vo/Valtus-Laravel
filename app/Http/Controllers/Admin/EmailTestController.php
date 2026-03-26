<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Mail\OrderCreatedNotification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function index()
    {
        // Apply email config first to get current active config
        $this->applyEmailConfig();
        
        // Get email config from settings (database)
        $emailConfigFromDB = [
            'mailer' => Setting::getValue('mail_mailer', 'log'),
            'host' => Setting::getValue('mail_host', ''),
            'port' => Setting::getValue('mail_port', '587'),
            'username' => Setting::getValue('mail_username', ''),
            'from_address' => Setting::getValue('mail_from_address', 'hello@example.com'),
            'from_name' => Setting::getValue('mail_from_name', 'Valtus'),
            'encryption' => Setting::getValue('mail_encryption', 'tls'),
        ];
        
        // Get actual active config (what Laravel is using now)
        $emailConfigActive = [
            'mailer' => config('mail.default', 'not set'),
            'host' => config('mail.mailers.smtp.host', 'not set'),
            'port' => config('mail.mailers.smtp.port', 'not set'),
            'username' => config('mail.mailers.smtp.username', 'not set'),
            'password' => config('mail.mailers.smtp.password') ? '***' . substr(config('mail.mailers.smtp.password'), -4) : 'not set',
            'from_address' => config('mail.from.address', 'not set'),
            'from_name' => config('mail.from.name', 'not set'),
            'encryption' => config('mail.mailers.smtp.encryption', 'not set'),
            'timeout' => config('mail.mailers.smtp.timeout', 'not set'),
        ];
        
        // Note: .env tidak dipakai untuk email config (pakai database saja)
        
        $emailConfig = $emailConfigFromDB;
        
        // Get recent email logs (last 50 lines)
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $logLines = explode("\n", $logContent);
            // Get last 100 lines that contain "email" or "mail"
            $emailLogs = array_filter($logLines, function($line) {
                return stripos($line, 'email') !== false || 
                       stripos($line, 'mail') !== false ||
                       stripos($line, 'Attempting to send') !== false ||
                       stripos($line, 'Failed to send') !== false ||
                       stripos($line, 'sent successfully') !== false;
            });
            $logs = array_slice($emailLogs, -50); // Get last 50 email-related logs
        }
        
        return view('admin.email-test', compact('emailConfig', 'emailConfigActive', 'logs'));
    }
    
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
            'email_type' => 'required|in:order_created,payment_confirmed,order_processed',
        ]);
        
        try {
            // Apply email config from settings
            $this->applyEmailConfig();
            
            $testEmail = $request->test_email;
            $emailType = $request->email_type;
            
            // Create a dummy order for testing
            $dummyOrder = new Order();
            $dummyOrder->order_id = 'TEST-' . strtoupper(uniqid());
            $dummyOrder->username = 'testuser';
            $dummyOrder->email = $testEmail;
            $dummyOrder->game_type = 'Robux';
            $dummyOrder->amount = (float) 1000;
            $dummyOrder->price = (float) 100000;
            $dummyOrder->payment_status = 'waiting_confirmation';
            $dummyOrder->order_status = 'pending';
            $dummyOrder->purchase_method = 'gamepass';
            
            // Send test email based on type
            $mailSent = false;
            $error = null;
            
            Log::info('=== EMAIL TEST STARTED ===', [
                'test_email' => $testEmail,
                'email_type' => $emailType,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'from' => config('mail.from.address'),
            ]);
            
            // Create Swift_Message with timeout settings
            switch ($emailType) {
                case 'order_created':
                    Mail::to($testEmail)->send(new \App\Mail\OrderCreatedNotification($dummyOrder));
                    $mailSent = true;
                    break;
                case 'payment_confirmed':
                    $dummyOrder->payment_status = 'Completed';
                    Mail::to($testEmail)->send(new \App\Mail\PaymentConfirmedNotification($dummyOrder));
                    $mailSent = true;
                    break;
                case 'order_processed':
                    $dummyOrder->payment_status = 'Completed';
                    $estimatedCompletion = '5 hari ke depan';
                    Mail::to($testEmail)->send(new \App\Mail\OrderProcessedNotification($dummyOrder, $estimatedCompletion));
                    $mailSent = true;
                    break;
            }
            
            Log::info('=== EMAIL TEST SUCCESS ===', [
                'test_email' => $testEmail,
                'email_type' => $emailType,
                'mail_sent' => $mailSent,
            ]);
            
            return redirect()->back()->with('success', "Test email berhasil dikirim ke {$testEmail}. Cek inbox/spam folder Anda.");
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            Log::error('=== EMAIL TEST FAILED ===', [
                'test_email' => $request->test_email,
                'email_type' => $request->email_type,
                'error' => $errorMessage,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()->with('error', "Gagal mengirim test email: {$errorMessage}");
        }
    }
    
    /**
     * Apply email configuration from database settings
     * Real case: Pakai database settings (bukan .env)
     */
    private function applyEmailConfig()
    {
        try {
            // PAKAI DATABASE SETTINGS (Real Case Implementation)
            $mailer = Setting::getValue('mail_mailer', 'log');
            $host = Setting::getValue('mail_host', '');
            $port = Setting::getValue('mail_port', '587');
            $username = Setting::getValue('mail_username', '');
            $password = Setting::getValue('mail_password', '');
            $encryption = Setting::getValue('mail_encryption', 'tls');
            $fromAddress = Setting::getValue('mail_from_address', 'hello@example.com');
            $fromName = Setting::getValue('mail_from_name', 'Valtus');
            
            Log::info('📧 Using email config from Database Settings', [
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption
            ]);
            
            // Normalize encryption
            if ($encryption === 'null' || $encryption === null || $encryption === '') {
                $encryption = null;
            }
            
            config([
                'mail.default' => $mailer ?: 'log',
                'mail.mailers.smtp.host' => $host ?: '127.0.0.1',
                'mail.mailers.smtp.port' => $port ?: '2525',
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout' => 60, // 60 seconds timeout
                'mail.from.address' => $fromAddress ?: 'hello@example.com',
                'mail.from.name' => $fromName ?: 'Valtus',
            ]);
            
            Log::info('Email config applied from Database Settings', [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                'from' => config('mail.from.address'),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to apply email config from database: ' . $e->getMessage());
        }
    }
}
