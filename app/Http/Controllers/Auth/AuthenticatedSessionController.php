<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'message' => session('message'),
            'throttle' => session('throttle'), // Add this line to pass the throttle information to the view
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $key = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $remainingAttempts = RateLimiter::retriesLeft($key, 5);
            return redirect()->route('login')->with('throttle', [
                'remaining' => $remainingAttempts,
                'seconds' => $seconds,
            ]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        RateLimiter::clear($key);

        $user = auth()->user();

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'You need to verify your email address before logging in.');
        }

        if ($user->role === 'admin') {
            // If the user is an admin, redirect to the admin dashboard
            return redirect()->intended(route('admin.dashboard', false));
        } elseif ($user->role === 'farmer') {
            // If the user is a farmer, redirect to the farmer dashboard
            return redirect()->intended(route('farmer.dashboard', false));
        } elseif ($user->role === 'buyer') {
            // If the user is a buyer, redirect to the buyer dashboard
            return redirect()->intended(route('buyer.dashboard', false))->with('success', 'Login successful');
        } else {
            // Handle other roles or redirect to a default dashboard
            return redirect()->intended(route('dashboard', false));
        }
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