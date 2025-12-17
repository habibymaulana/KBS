<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user && $user->role === 'siswa') {
            $today = now()->startOfDay();
            $last = $user->last_login_at ? $user->last_login_at->copy()->startOfDay() : null;

            if (! $last) {
                $user->streak_days = 1;
            } else {
                $diff = $last->diffInDays($today);
                if ($diff === 0) {
                    // login di hari yang sama, tidak ubah streak
                } elseif ($diff === 1) {
                    $user->streak_days = ($user->streak_days ?? 0) + 1;
                } else {
                    $user->streak_days = 1;
                }
            }

            $user->last_login_at = now();

            // +25 poin jika mencapai atau melewati 7 hari streak
            if ($user->streak_days >= 7 && ($user->points ?? 0) < 25 * $user->streak_days) {
                $user->addPoints(25);
            } else {
                $user->save();
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
