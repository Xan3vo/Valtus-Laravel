<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Blacklist::query()->orderByDesc('created_at');
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('username', 'like', '%' . $q . '%')
                    ->orWhere('reason', 'like', '%' . $q . '%');
            });
        }

        $blacklists = $query->paginate(25)->appends($request->query());

        return view('admin.blacklists', compact('blacklists', 'q'));
    }

    public function create()
    {
        return view('admin.blacklist-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'reason' => 'nullable|string',
            'banned_until' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $username = trim((string) $request->username);
        $usernameLower = Blacklist::normalizeUsername($username);

        if (Blacklist::where('username_lower', $usernameLower)->exists()) {
            return back()->withErrors(['username' => 'Username sudah ada di blacklist.'])->withInput();
        }

        Blacklist::create([
            'username' => $username,
            'username_lower' => $usernameLower,
            'reason' => $request->reason,
            'banned_until' => $request->banned_until,
            'is_active' => $request->boolean('is_active', true),
            'created_by_admin_id' => auth('admin')->id(),
        ]);

        return redirect()->route('admin.blacklists')->with('success', 'Blacklist berhasil ditambahkan.');
    }

    public function edit(Blacklist $blacklist)
    {
        return view('admin.blacklist-form', compact('blacklist'));
    }

    public function update(Request $request, Blacklist $blacklist)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'reason' => 'nullable|string',
            'banned_until' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $username = trim((string) $request->username);
        $usernameLower = Blacklist::normalizeUsername($username);

        $exists = Blacklist::where('username_lower', $usernameLower)
            ->where('id', '!=', $blacklist->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['username' => 'Username sudah ada di blacklist.'])->withInput();
        }

        $blacklist->update([
            'username' => $username,
            'username_lower' => $usernameLower,
            'reason' => $request->reason,
            'banned_until' => $request->banned_until,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.blacklists')->with('success', 'Blacklist berhasil diperbarui.');
    }

    public function destroy(Blacklist $blacklist)
    {
        $blacklist->delete();
        return redirect()->route('admin.blacklists')->with('success', 'Blacklist berhasil dihapus.');
    }

    public function toggleStatus(Blacklist $blacklist)
    {
        $blacklist->update(['is_active' => !$blacklist->is_active]);
        return redirect()->route('admin.blacklists')->with('success', 'Status blacklist berhasil diperbarui.');
    }
}
