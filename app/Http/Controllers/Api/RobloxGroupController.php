<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RobloxGroupController extends Controller
{
    /**
     * Get Group ID from settings
     */
    private function getGroupId()
    {
        return (int) Setting::getValue('group_id', '35148970');
    }
    
    /**
     * Get minimum membership days from settings
     */
    private function getMinMembershipDays()
    {
        return (int) Setting::getValue('min_membership_days', '14');
    }
    
    /**
     * Get group name from settings
     */
    private function getGroupName()
    {
        return Setting::getValue('group_name', 'Valtus Studios');
    }

    /**
     * Check if user is a member of the group and if they can purchase
     */
    public function checkGroupMembership(Request $request)
    {
        $username = trim($request->input('username', ''));
        
        if (empty($username)) {
            return response()->json([
                'success' => false,
                'message' => 'Username tidak boleh kosong'
            ], 400);
        }

        try {
            // Step 1: Get user ID from username
            $userId = $this->getUserIdFromUsername($username);
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username tidak ditemukan'
                ], 404);
            }

            // Step 2: Check group membership
            $membershipData = $this->checkGroupMembershipForUser($userId);
            
            // Get group name and link from settings
            $groupName = $this->getGroupName();
            $groupLink = Setting::getValue('group_link', 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about');
            
            if (!$membershipData['is_member']) {
                return response()->json([
                    'success' => false,
                    'is_member' => false,
                    'group_name' => $groupName,
                    'group_link' => $groupLink,
                    'message' => 'Anda belum bergabung dengan group ' . $groupName . '. Silakan bergabung terlebih dahulu.'
                ]);
            }

            // Step 3: Just return member status, no duration validation
            return response()->json([
                'success' => true,
                'is_member' => true,
                'group_name' => $groupName,
                'group_link' => $groupLink,
                'membership_days' => $membershipData['membership_days'] ?? 0,
                'message' => 'Anda sudah bergabung dengan group ' . $groupName . '.'
            ]);

        } catch (\Exception $e) {
            Log::error('Group membership check failed', [
                'username' => $username,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek keanggotaan group. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Get user ID from username
     */
    private function getUserIdFromUsername($username)
    {
        try {
            $response = Http::timeout(10)->post('https://users.roblox.com/v1/usernames/users', [
                'usernames' => [$username],
                'excludeBannedUsers' => true,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $userData = $data['data'][0] ?? null;
            
            return $userData['id'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to get user ID', [
                'username' => $username,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check group membership for a specific user
     */
    private function checkGroupMembershipForUser($userId)
    {
        try {
            // Check if user is in the group
            $response = Http::timeout(10)->get("https://groups.roblox.com/v1/users/{$userId}/groups/roles");
            
            if (!$response->successful()) {
                return [
                    'is_member' => false,
                    'membership_days' => 0,
                    'joined_date' => null
                ];
            }

            $data = $response->json();
            $groups = $data['data'] ?? [];

            // Look for our group
            $groupId = $this->getGroupId();
            foreach ($groups as $group) {
                if ($group['group']['id'] == $groupId) {
                    $joinedDate = $group['role']['created'] ?? null;
                    $membershipDays = 0;
                    
                    if ($joinedDate) {
                        $joined = new \DateTime($joinedDate);
                        $now = new \DateTime();
                        $membershipDays = $now->diff($joined)->days;
                    }

                    return [
                        'is_member' => true,
                        'membership_days' => $membershipDays,
                        'joined_date' => $joinedDate
                    ];
                }
            }

            return [
                'is_member' => false,
                'membership_days' => 0,
                'joined_date' => null
            ];

        } catch (\Exception $e) {
            Log::error('Failed to check group membership', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'is_member' => false,
                'membership_days' => 0,
                'joined_date' => null
            ];
        }
    }

    /**
     * Get group information
     */
    public function getGroupInfo()
    {
        try {
            $groupId = $this->getGroupId();
            $response = Http::timeout(10)->get("https://groups.roblox.com/v1/groups/{$groupId}");
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan informasi group'
                ], 500);
            }

            $data = $response->json();
            
            // Get group link from settings or use default
            $groupLink = Setting::getValue('group_link', "https://www.roblox.com/communities/{$data['id']}/{$data['name']}#!/about");
            
            return response()->json([
                'success' => true,
                'group' => [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'member_count' => $data['memberCount'],
                    'url' => $groupLink
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get group info', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan informasi group'
            ], 500);
        }
    }
}
