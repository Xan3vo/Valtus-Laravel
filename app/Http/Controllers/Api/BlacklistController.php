<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    // GET /api/blacklist/check?username=foo
    public function check(Request $request)
    {
        $username = trim((string) $request->query('username', ''));
        if ($username === '') {
            return response()->json(['ok' => false, 'error' => 'EMPTY_USERNAME'], 400);
        }

        $entry = Blacklist::getBlockEntry($username);
        if (!$entry) {
            return response()->json(['ok' => true, 'blocked' => false]);
        }

        return response()->json([
            'ok' => true,
            'blocked' => true,
            'username' => $entry->username,
            'banned_until' => $entry->banned_until ? $entry->banned_until->toISOString() : null,
        ]);
    }
}
