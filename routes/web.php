<?php

use App\Livewire\HomePage;
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

Route::get('/', HomePage::class);
Route::get('/categories', \App\Livewire\CategoriesPage::class);
Route::get('/cart', \App\Livewire\CartPage::class);

Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\LoginPage::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\RegisterPage::class);
    Route::get('/forgot', \App\Livewire\Auth\ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}', \App\Livewire\Auth\ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/');
    });
    Route::get('/checkout', \App\Livewire\CheckoutPage::class);
    Route::get('/my-orders', \App\Livewire\MyOrderPage::class)->name('my-orders');
    // Route::get('/my-orders/{order_id}', \App\Livewire\MyOrderDetailPage::class)->name('my-orders.show');

    // Route::get('/success', \App\Livewire\SuccessPage::class)->name('success');
    // Route::get('/cancel', \App\Livewire\CancelPage::class)->name('cancel');
});
