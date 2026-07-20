<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\BranchContentController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HeroController;
use App\Http\Controllers\Api\MostOrderedController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/hero', [HeroController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/branch-content', [BranchContentController::class, 'index']);
Route::get('/branches', [BranchController::class, 'index']);
Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/menu', [ProductController::class, 'menu']);
Route::get('/most-ordered', [MostOrderedController::class, 'index']);
