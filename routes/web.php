<?php

use App\Livewire\Ticket\Create;
use App\Livewire\Ticket\Index;
use App\Livewire\Ticket\Show;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::livewire('/', Index::class)->name('index');
        Route::livewire('/create', Create::class)->name('create');
        Route::livewire('/{ticket}', Show::class)->name('show');
    });
});

require __DIR__.'/settings.php';
