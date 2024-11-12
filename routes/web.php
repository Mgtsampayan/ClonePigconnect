<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\FarmInformationController;
use App\Http\Controllers\PigFarmInformationController;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Email Verification Routes
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    Log::info('Verification request received', ['id' => $request->route('id'), 'hash' => $request->route('hash')]);

    $request->fulfill();

    // Check if the email_verified_at field is updated
    $user = $request->user();
    Log::info('User verified', ['user' => $user]);

    // Log the user out and redirect to the login page
    Auth::logout();
    return redirect()->route('login')->with('message', 'Your email has been verified. Please log in.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('login');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/expenses', function () {
    return Inertia::render('FarmerView/Expenses');
})->middleware(['auth', 'verified'])->name('farmer.expenses');

Route::post('/pigfarminformation', [PigFarmInformationController::class, 'store'])->middleware(['auth', 'verified']);

Route::get('/pigfarminformation', function () {
    return Inertia::render('FarmerView/FarmInformation');
})->middleware(['auth', 'verified'])->name('farmer.pigfarminformation');

Route::get('/breedingrecord', function () {
    return Inertia::render('FarmerView/BreedingRecord');
})->middleware(['auth', 'verified'])->name('farmer.breedingrecord');

Route::get('/farmerdashboard', function () {
    return Inertia::render('FarmerDashboard');
})->middleware(['auth', 'verified'])->name('farmer.dashboard');

Route::get('/piginformation', function () {
    return Inertia::render('FarmerView/PigInformation');
})->middleware(['auth', 'verified'])->name('pig.information');

Route::get('/buyerdashboard', function () {
    return Inertia::render('BuyerDashboard');
})->middleware(['auth', 'verified'])->name('buyer.dashboard');

Route::get('/admin', function () {
    return Inertia::render('AdminDashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::get('/chat', function () {
    return Inertia::render('Chat');
})->middleware(['auth', 'verified'])->name('chat');

Route::get('/buyerpreferences', function () {
    return Inertia::render('BuyerView/BuyersPreference');
})->middleware(['auth', 'verified'])->name('buyer.preferences');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';