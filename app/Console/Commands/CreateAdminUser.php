<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = Admin::where('email', 'admin@valtus.com')->first();
        
        if ($admin) {
            $this->info('Admin user already exists: ' . $admin->email);
            $this->info('Updating password...');
            $admin->update(['password' => Hash::make('admin123')]);
            $this->info('Password updated successfully!');
        } else {
            $this->info('Creating admin user...');
            Admin::create([
                'name' => 'Admin Valtus',
                'email' => 'admin@valtus.com',
                'password' => Hash::make('admin123'),
            ]);
            $this->info('Admin user created successfully!');
        }
        
        $this->info('Login credentials:');
        $this->info('Email: admin@valtus.com');
        $this->info('Password: admin123');
    }
}