<?php

use App\Http\Controllers\Api\ErpSsoTokenController;
use Illuminate\Support\Facades\Route;

Route::post('/erp/sso-tokens', [ErpSsoTokenController::class, 'store'])
    ->middleware('erp.api')
    ->name('api.erp.sso-tokens.store');