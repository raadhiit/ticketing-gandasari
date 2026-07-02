<?php

use App\Livewire\ActivityLog\Index as ActivityLog;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Report\Index as Report;
use App\Livewire\Ticket\Create;
use App\Livewire\Ticket\Edit;
use App\Livewire\Ticket\Index;
use App\Livewire\Ticket\Show;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::livewire('/', Index::class)->name('index');
        Route::livewire('/create', Create::class)->name('create');
        Route::livewire('/{ticket}', Show::class)->name('show');
        Route::livewire('/{ticket}/edit', Edit::class)->name('edit');
    });

    Route::livewire('/reports', Report::class)
        ->middleware('can:report.view')
        ->name('reports');

    Route::livewire('/activity-log', ActivityLog::class)
        ->middleware('can:activity-log.view')
        ->name('activity-log');
});

require __DIR__.'/settings.php';
