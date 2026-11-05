<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pengguna.
     */
    public function show(): View
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    /**
     * Update data profil pengguna (nama, email, no telepon, alamat rumah).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'   => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'name.max'       => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
            'phone.max'      => 'Nomor telepon maksimal 25 digit.',
            'address.max'    => 'Alamat rumah maksimal 1000 karakter.',
        ]);

        $user->update([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'phone'   => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()->route('profile.show')->with('success_profile', 'Profil berhasil diperbarui.');
    }

    /**
     * Update kata sandi pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required'         => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required'                 => 'Kata sandi baru wajib diisi.',
            'password.min'                      => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak sama.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.show')->with('success_password', 'Kata sandi berhasil diubah.');
    }
}
