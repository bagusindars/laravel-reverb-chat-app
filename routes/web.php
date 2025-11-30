<?php

use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest.agent')->group( function () {
    Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
});

Route::middleware('auth.agent')->group( function () {
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::get('/chat/live-chat', App\Livewire\Dashboard\LiveChat::class)->name('chat.live-chat');
    Route::get('/master-customer', App\Livewire\Dashboard\MasterCustomer::class)->name('master-customer');
});