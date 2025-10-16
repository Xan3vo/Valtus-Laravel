<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RobloxAvatarService
{
    /**
     * Get user avatar URL from Roblox API (optimized for speed)
     */
    public static function getAvatarUrl($username, $size = '150x150')
    {
        // Cache key for this username and size
        $cacheKey = "roblox_avatar_{$username}_{$size}";
        
        // Check cache first (cache for 12 hours to reduce API calls)
        return Cache::remember($cacheKey, 43200, function () use ($username, $size) {
            try {
                // First, get user ID from username
                $userId = self::getUserIdFromUsername($username);
                
                if (!$userId) {
                    return self::getDefaultAvatar();
                }
                
                // Get avatar URL from user ID
                $avatarUrl = self::getAvatarUrlFromUserId($userId, $size);
                
                return $avatarUrl ?: self::getDefaultAvatar();
                
            } catch (\Exception $e) {
                Log::warning('roblox.avatar.failed', [
                    'username' => $username,
                    'error' => $e->getMessage()
                ]);
                
                return self::getDefaultAvatar();
            }
        });
    }
    
    /**
     * Get user ID from username using Roblox API
     */
    private static function getUserIdFromUsername($username)
    {
        // Cache user ID for 48 hours to reduce API calls
        $cacheKey = "roblox_user_id_{$username}";
        
        return Cache::remember($cacheKey, 172800, function () use ($username) {
            try {
                // Gunakan POST request yang lebih reliable
                $response = Http::timeout(2)->post("https://users.roblox.com/v1/usernames/users", [
                    'usernames' => [$username]
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['data']) && count($data['data']) > 0) {
                        $userId = $data['data'][0]['id'] ?? null;
                        Log::info('roblox.username.post', [
                            'username' => $username,
                            'ok' => true,
                            'found' => !is_null($userId)
                        ]);
                        return $userId;
                    }
                }
                
                Log::warning('roblox.username.get_failed', [
                    'username' => $username,
                    'status' => $response->status()
                ]);
                
                return null;
                
            } catch (\Exception $e) {
                Log::warning('roblox.username.exception', [
                    'username' => $username,
                    'error' => $e->getMessage()
                ]);
                
                return null;
            }
        });
    }
    
    /**
     * Get avatar URL from user ID
     */
    private static function getAvatarUrlFromUserId($userId, $size = '150x150')
    {
        try {
            $response = Http::timeout(2)->get("https://thumbnails.roblox.com/v1/users/avatar", [
                'userIds' => [$userId],
                'size' => $size,
                'format' => 'Png',
                'isCircular' => 'true'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['data']) && count($data['data']) > 0) {
                    $avatarUrl = $data['data'][0]['imageUrl'] ?? null;
                    Log::info('roblox.avatar.ok', [
                        'userId' => $userId,
                        'hasAvatar' => !is_null($avatarUrl)
                    ]);
                    return $avatarUrl;
                }
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::warning('roblox.avatar.failed', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
    
    /**
     * Get default avatar when Roblox API fails
     */
    private static function getDefaultAvatar()
    {
        return 'https://www.roblox.com/Thumbs/Avatar.ashx?userId=1&x=150&y=150&Format=Png';
    }
    
    /**
     * Batch get multiple avatars
     */
    public static function getMultipleAvatars($usernames, $size = '150x150')
    {
        $results = [];
        
        foreach ($usernames as $username) {
            $results[$username] = self::getAvatarUrl($username, $size);
        }
        
        return $results;
    }
    
    /**
     * Clear avatar cache for a specific username
     */
    public static function clearAvatarCache($username)
    {
        $sizes = ['150x150', '100x100', '200x200'];
        
        foreach ($sizes as $size) {
            Cache::forget("roblox_avatar_{$username}_{$size}");
        }
    }
    
    /**
     * Get avatar with fallback to initials
     */
    public static function getAvatarWithFallback($username, $size = '150x150')
    {
        try {
            $avatarUrl = self::getAvatarUrl($username, $size);
            
            // If we got a real avatar URL, return it
            if ($avatarUrl && $avatarUrl !== self::getDefaultAvatar()) {
                return [
                    'found' => true,
                    'avatar' => $avatarUrl,
                    'type' => 'image',
                    'url' => $avatarUrl,
                    'initials' => strtoupper(substr($username, 0, 1))
                ];
            }
            
            // Fallback to initials
            return [
                'found' => false,
                'avatar' => null,
                'type' => 'initials',
                'initials' => strtoupper(substr($username, 0, 1)),
                'username' => $username
            ];
        } catch (\Exception $e) {
            // Return safe fallback on any error
            return [
                'found' => false,
                'avatar' => null,
                'type' => 'initials',
                'initials' => strtoupper(substr($username, 0, 1)),
                'username' => $username,
                'error' => $e->getMessage()
            ];
        }
    }
}
