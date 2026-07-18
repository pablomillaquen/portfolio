<?php

use App\Http\Controllers\Api\V1\CapabilityController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\SeasonController;
use Illuminate\Support\Facades\Route;

Route::get('projects', [ProjectController::class, 'index']);
Route::get('projects/{slug}', [ProjectController::class, 'show']);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);

Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{slug}', [CourseController::class, 'show']);

Route::get('seasons', [SeasonController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('capabilities', [CapabilityController::class, 'index']);
