<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Departments;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Security;
use App\Livewire\Settings\TicketCategories;
use App\Livewire\Settings\Users;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::livewire('settings/security', Security::class)
        ->middleware([
            'password.confirm',
        ])
        ->name('security.edit');
});

Route::middleware(['auth', 'verified', 'can:settings.manage'])->group(function () {
    Route::livewire('settings/departments', Departments::class)->name('settings.departments');
    Route::livewire('settings/ticket-categories', TicketCategories::class)->name('settings.ticket-categories');
    Route::livewire('settings/users', Users::class)->name('settings.users');
});
