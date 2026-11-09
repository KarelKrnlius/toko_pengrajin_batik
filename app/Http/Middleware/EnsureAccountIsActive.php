<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Jika bukan admin dan akun berstatus nonaktif
            if (! $user->isAdmin() && isset($user->is_active) && ! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Akunmu sudah dinonaktifkan.',
                    ], 403);
                }

                return redirect()->route('login')
                    ->with('error', 'Akunmu sudah dinonaktifkan.');
            }
        }

        return $next($request);
    }
}
