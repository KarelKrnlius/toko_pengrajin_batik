<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /** Tampilkan form login */
    public function showLogin()
    {
        if (Auth::check()) {
            if (! Auth::user()->isAdmin() && isset(Auth::user()->is_active) && ! Auth::user()->is_active) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Akunmu sudah dinonaktifkan.');
            }

            return Auth::user()->isAdmin()
                ? redirect()->route('admin.products.index')
                : redirect()->route('home');
        }
        return view('auth.login');
    }

    /** Tampilkan form register */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /** Proses registrasi user baru */
    public function register(Request $request)
    {
        // Normalisasi email ke huruf kecil & hilangkan spasi luar
        $email = strtolower(trim((string) $request->input('email')));
        $request->merge(['email' => $email]);

        // Cek secara eksplisit apakah akun dengan email ini sudah terdaftar di database
        if (! empty($email) && User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['email' => 'Akun sudah terdaftar. Silakan login menggunakan akun Anda atau gunakan email lain.']);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Akun sudah terdaftar.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama dengan kata sandi.',
        ]);

        $userData = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => 'user',
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'is_active')) {
            $userData['is_active'] = true;
        }

        $user = User::create($userData);

        // Tidak auto-login — arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan masuk dengan email dan password Anda.');
    }

    /** Proses login */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Cek jika akun berstatus nonaktif sebelum attempt
        $user = User::where('email', $credentials['email'])->first();
        if ($user && ! $user->isAdmin() && isset($user->is_active) && ! $user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akunmu sudah dinonaktifkan.');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Proteksi jika akun nonaktif
            if (! $user->isAdmin() && isset($user->is_active) && ! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Akunmu sudah dinonaktifkan.');
            }

            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.products.index'));
            }
            return redirect()->intended(route('home'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password tidak sesuai.']);
    }

    /** Logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
