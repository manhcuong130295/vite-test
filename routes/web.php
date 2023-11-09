<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');
Route::middleware(['auth', 'check_user_status', 'logout_users'])->group(function () {
    Route::prefix('payment')->group(function () {
        Route::get('list', [PaymentController::class, 'showCheckoutPage'])->name('payment.list');
        Route::post('list', [PaymentController::class, 'processPayment'])->name('payment.process');
    });
    //Stripe
    Route::prefix('stripe')->group(function () {
        Route::get('success', [StripeController::class, 'success'])->name('stripe.success');
        Route::get('cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
        Route::post('checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
    });
    //Route project
    Route::controller(ProjectController::class)->group(function () {
        Route::get('project/list', 'index')->name('project.list');
        Route::get('project/create', 'create')->name('project.create');
        Route::get('project/update/{id}', 'update')->name('project.update');
    });
    //Profile
     Route::get('profile', [UserController::class, 'profile'])->name('profile');
});

// Route::post('webhook', [StripeController::class, 'handleWebhook']);
// Route::post('webhookLine', [LineController::class, 'handleWebhook']);


// Route::get('login', [LoginController::class, 'login'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('auth/google/callback', [LoginController::class, 'handleCallback'])->name('google.callback');
Route::post("/chat-stream", [ChatController::class, 'chatStream'])->name('web.chat');
Route::get('/policy', function () {
    return view('policy');
})->name('policy');



