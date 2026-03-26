<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Order Observer
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        
        // Apply email configuration from settings
        $this->applyEmailConfigFromSettings();
    }
    
    /**
     * Apply email configuration from database settings
     */
    private function applyEmailConfigFromSettings()
    {
        try {
            // PAKAI DATABASE SETTINGS (Real Case Implementation)
            $mailer = \App\Models\Setting::getValue('mail_mailer', 'log');
            $host = \App\Models\Setting::getValue('mail_host', '');
            $port = \App\Models\Setting::getValue('mail_port', '587');
            $username = \App\Models\Setting::getValue('mail_username', '');
            $password = \App\Models\Setting::getValue('mail_password', '');
            $encryption = \App\Models\Setting::getValue('mail_encryption', 'tls');
            $fromAddress = \App\Models\Setting::getValue('mail_from_address', 'hello@example.com');
            $fromName = \App\Models\Setting::getValue('mail_from_name', 'Valtus');
            
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
        } catch (\Exception $e) {
            // If settings table doesn't exist yet, skip
            // This prevents errors during migration
        }
    }
}
