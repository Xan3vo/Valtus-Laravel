<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $fillable = [
        'username',
        'username_lower',
        'reason',
        'is_active',
        'banned_until',
        'created_by_admin_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'banned_until' => 'datetime',
    ];

    public function isBlocked(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->banned_until === null) {
            return true;
        }

        return now()->lt($this->banned_until);
    }

    public static function normalizeUsername(string $username): string
    {
        return mb_strtolower(trim($username));
    }

    public static function isUsernameBlocked(string $username): bool
    {
        $normalized = self::normalizeUsername($username);
        if ($normalized === '') {
            return false;
        }

        $entry = self::where('username_lower', $normalized)->first();
        if (!$entry) {
            return false;
        }

        return $entry->isBlocked();
    }

    public static function getBlockEntry(string $username): ?self
    {
        $normalized = self::normalizeUsername($username);
        if ($normalized === '') {
            return null;
        }

        $entry = self::where('username_lower', $normalized)->first();
        if (!$entry) {
            return null;
        }

        return $entry->isBlocked() ? $entry : null;
    }
}
