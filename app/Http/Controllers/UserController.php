<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const DEFAULT_PASSWORD = 'juraganbatik345';

    /** Daftar user/admin dengan filter, sortir, dan pagination 10/halaman */
    public function index(Request $request)
    {
        $role = $request->get('role', 'user');
        if (! in_array($role, ['user', 'admin'])) {
            $role = 'user';
        }

        $hasIsActive = Schema::hasColumn('users', 'is_active');

        $query = User::query()->where('role', $role);

        // Untuk tab admin: tampilkan semua admin (termasuk diri sendiri agar terlihat)
        // Untuk tab user: sembunyikan diri sendiri
        if ($role === 'user') {
            $query->where('id', '!=', Auth::id());
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'ilike', "%{$q}%")
                    ->orWhere('email', 'ilike', "%{$q}%")
                    ->orWhere('phone', 'ilike', "%{$q}%");
            });
        }

        // Filter status (hanya untuk role user dan jika kolom is_active ada)
        if ($role === 'user' && $hasIsActive && $request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sortir
        match ($request->get('sort', 'terbaru')) {
            'terlama'  => $query->oldest(),
            'nama_az'  => $query->orderBy('name'),
            'nama_za'  => $query->orderByDesc('name'),
            default    => $query->latest(),
        };

        $users = $query->paginate(10)->withQueryString();

        // Hitung total admin untuk proteksi hapus admin terakhir
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'role', 'totalAdmins', 'hasIsActive'));
    }

    /** Tambah user baru */
    public function store(Request $request)
    {
        $hasIsActive = Schema::hasColumn('users', 'is_active');

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:user,admin',
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
            'password' => $validated['password'],
            'role'     => $validated['role'],
        ];

        if ($hasIsActive) {
            $data['is_active'] = true;
        }

        User::create($data);

        $roleLabel = $validated['role'] === 'admin' ? 'Admin' : 'User';

        return redirect()->route('admin.users.index', ['role' => $validated['role']])
            ->with('success', "{$roleLabel} berhasil ditambahkan.");
    }

    /** Edit data user (nama, email, no telepon) */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah dipakai akun lain.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Data user berhasil diperbarui.');
    }

    /** Reset password ke password default */
    public function resetPassword(User $user)
    {
        $user->update(['password' => self::DEFAULT_PASSWORD]);

        return back()->with('success', "Password {$user->name} berhasil direset ke password default.");
    }

    /** Toggle aktif / nonaktif (hanya untuk role user) */
    public function toggleStatus(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Status admin tidak dapat diubah.');
        }

        if (! Schema::hasColumn('users', 'is_active')) {
            return back()->with('error', 'Kolom is_active belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    /** Hapus satu akun */
    public function destroy(User $user)
    {
        // Jangan hapus diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Proteksi admin terakhir
        if ($user->isAdmin()) {
            $totalAdmins = User::where('role', 'admin')->count();
            if ($totalAdmins <= 1) {
                return back()->with('error', 'Tidak dapat menghapus admin terakhir. Minimal harus ada 1 admin.');
            }
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun {$name} berhasil dihapus.");
    }

    /** Hapus banyak akun sekaligus */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada user yang dipilih.');
        }

        // Jangan hapus diri sendiri meski dipilih
        $ids = array_filter($ids, fn ($id) => (int) $id !== Auth::id());

        // Proteksi admin terakhir: jangan hapus jika akan menyisakan 0 admin
        $adminIdsToDelete = User::whereIn('id', $ids)->where('role', 'admin')->pluck('id')->toArray();
        if (! empty($adminIdsToDelete)) {
            $totalAdmins = User::where('role', 'admin')->count();
            if ($totalAdmins - count($adminIdsToDelete) < 1) {
                return back()->with('error', 'Tidak dapat menghapus semua admin. Minimal harus ada 1 admin tersisa.');
            }
        }

        $count = User::whereIn('id', $ids)->count();
        User::whereIn('id', $ids)->delete();

        return back()->with('success', "{$count} akun berhasil dihapus.");
    }
}
