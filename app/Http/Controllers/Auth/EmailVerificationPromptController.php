<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect based on user role
            $user = $request->user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard', false));
            } elseif ($user->role === 'farmer') {
                return redirect()->intended(route('farmer.dashboard', false));
            } elseif ($user->role === 'buyer') {
                return redirect()->intended(route('buyer.dashboard', false));
            } else {
                return redirect()->intended(route('dashboard', false));
            }
        }

        return Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}