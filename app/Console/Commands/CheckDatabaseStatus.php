<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Setting;

class CheckDatabaseStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check database status and table counts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Database Status:');
        $this->info('================');
        
        $this->info('Users: ' . User::count());
        $this->info('Admins: ' . Admin::count());
        $this->info('Orders: ' . Order::count());
        $this->info('Settings: ' . Setting::count());
        
        $this->info('');
        $this->info('Admin Login Credentials:');
        $this->info('Email: admin@valtus.com');
        $this->info('Password: admin123');
    }
}