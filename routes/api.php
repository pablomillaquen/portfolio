<?php

use App\Http\Middleware\EnsureApiKey;
use Illuminate\Support\Facades\Route;

Route::middleware([EnsureApiKey::class])->group(function () {
    Route::prefix('v1')->name('v1.')->group(base_path('routes/api/v1.php'));
});
