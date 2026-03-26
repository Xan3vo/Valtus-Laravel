<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = Setting::getValue('maintenance_mode', '0') === '1';
        
        if ($maintenanceMode) {
            // Allow admin routes (including login) to bypass maintenance
            $path = $request->path();
            
            // Check if this is an admin route (starts with 'system')
            // Admin routes should always be accessible during maintenance
            if (str_starts_with($path, 'system')) {
                return $next($request);
            }
            
            // Block all other routes (user routes, API routes, etc.)
            // Show maintenance page
            return response()->view('maintenance', [
                'message' => Setting::getValue('maintenance_message', 'Website sedang dalam maintenance. Silakan coba lagi nanti.')
            ], 503);
        }
        
        return $next($request);
    }
}

