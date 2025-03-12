<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function() {
    // Public routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/verify-otp', 'verifyOtp');
        Route::post('/resend-otp', 'resendOtp');
        Route::post('/forgot-password', 'forgotPassword');
        Route::post('/reset-password', 'resetPassword');
    });

    // Protected routes
    Route::middleware('auth:sanctum')->controller(AuthController::class)->group(function () {
        Route::post('/refresh-token', 'refreshToken');
        Route::post('/address', 'addAddress');
        Route::post('/logout', 'logout');
    });

    Route::controller(UserProfileController::class)->group(function () {
        Route::post('complete-data', 'completeData');
    });

    Route::middleware('auth:sanctum')->group(function () {
        //Home
        Route::get('/slider', [HomeController::class, 'slider']);
        Route::get('/sections', [HomeController::class, 'getSections']);
        Route::get('/best-selling', [HomeController::class, 'bestSelling']);
        Route::apiResource('offers', OfferController::class)->only('index', 'show');
        //Section
       Route::get('/sections/{id}/categories', [SectionController::class, 'getCategoriesOfSection']);
       Route::get('/sections/{id}/ads', [SectionController::class, 'getAdsOfSection']);
       Route::get('categories/{categoryId}/ads', [SectionController::class, 'getAdsByCategory']);
       Route::get('/ads/{id}', [SectionController::class, 'showAd']);

    });

});
