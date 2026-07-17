<?php

use App\Http\Controllers\Api\AdminPreviewController;
use App\Http\Controllers\Api\AdminCourseController;
use App\Http\Controllers\Api\AdminPostController;
use App\Http\Controllers\Api\AdminProjectController;
use App\Http\Controllers\Api\AdminSiteSettingController;
use App\Http\Controllers\Api\AdminSocialLinkController;
use App\Http\Controllers\Api\AdminSeasonController;
use App\Http\Controllers\Api\AdminCategoryController;
use App\Http\Controllers\Api\AdminCapabilityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PublicContentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::prefix('api')->group(function (): void {
    Route::get('/home', [PublicContentController::class, 'home']);
    Route::get('/settings', [PublicContentController::class, 'settingsOnly']);
    Route::get('/projects', [PublicContentController::class, 'projects']);
    Route::get('/projects/{slug}', [PublicContentController::class, 'project']);
    Route::get('/posts', [PublicContentController::class, 'posts']);
    Route::get('/posts/{slug}', [PublicContentController::class, 'post']);
    Route::get('/courses', [PublicContentController::class, 'courses']);
    Route::get('/courses/{slug}', [PublicContentController::class, 'course']);
    Route::post('/contact', [ContactController::class, 'store']);

    Route::get('/capabilities', [PublicContentController::class, 'capabilities']);
    Route::get('/categories', [PublicContentController::class, 'categories']);
    Route::get('/seasons', [PublicContentController::class, 'seasons']);

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::middleware('admin.session')->prefix('admin')->group(function (): void {
        Route::post('/preview', [AdminPreviewController::class, 'preview']);

        Route::get('/projects', [AdminProjectController::class, 'index']);
        Route::post('/projects', [AdminProjectController::class, 'store']);
        Route::put('/projects/{project}', [AdminProjectController::class, 'update']);
        Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy']);

        Route::get('/courses', [AdminCourseController::class, 'index']);
        Route::post('/courses', [AdminCourseController::class, 'store']);
        Route::put('/courses/{course}', [AdminCourseController::class, 'update']);
        Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy']);

        Route::get('/posts', [AdminPostController::class, 'index']);
        Route::post('/posts', [AdminPostController::class, 'store']);
        Route::put('/posts/{post}', [AdminPostController::class, 'update']);
        Route::delete('/posts/{post}', [AdminPostController::class, 'destroy']);

        Route::get('/social-links', [AdminSocialLinkController::class, 'index']);
        Route::put('/social-links', [AdminSocialLinkController::class, 'save']);

        Route::get('/settings', [AdminSiteSettingController::class, 'index']);
        Route::put('/settings', [AdminSiteSettingController::class, 'save']);

        Route::get('/seasons', [AdminSeasonController::class, 'index']);
        Route::post('/seasons', [AdminSeasonController::class, 'store']);
        Route::put('/seasons/{season}', [AdminSeasonController::class, 'update']);
        Route::delete('/seasons/{season}', [AdminSeasonController::class, 'destroy']);

        Route::get('/categories', [AdminCategoryController::class, 'index']);
        Route::post('/categories', [AdminCategoryController::class, 'store']);
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);

        Route::get('/capabilities', [AdminCapabilityController::class, 'index']);
        Route::post('/capabilities', [AdminCapabilityController::class, 'store']);
        Route::put('/capabilities/{capability}', [AdminCapabilityController::class, 'update']);
        Route::delete('/capabilities/{capability}', [AdminCapabilityController::class, 'destroy']);
    });
});

Route::get('/{any?}', fn () => view('app'))
    ->where('any', '.*');


Route::get('/optimize-app', function () {
    Artisan::call('optimize');

    return nl2br(Artisan::output());
});
