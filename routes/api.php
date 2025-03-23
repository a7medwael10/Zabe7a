<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\favouriteController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PackagingOptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\UserProfileController;
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
        Route::post('/logout', 'logout');
    });

    Route::controller(UserProfileController::class)->group(function () {
        Route::post('complete-data', 'completeData');
        Route::get('profile', 'profileData');
        Route::post('update-profile', 'updateProfile');
    });

    Route::middleware('auth:sanctum')->group(function () {
        //Home
        Route::get('/slider', [HomeController::class, 'slider']);
        Route::get('/sections', [HomeController::class, 'getSections']);
        Route::get('/best-selling', [HomeController::class, 'bestSelling']);
        Route::get('/offers', [OfferController::class, 'getOffers']);

        //Section
       Route::get('/sections/{id}/categories', [SectionController::class, 'getCategoriesOfSection']);
       Route::get('/sections/{id}/ads', [SectionController::class, 'getAdsOfSection']);
       Route::get('categories/{categoryId}/ads', [AdController::class, 'getAdsByCategory']);
       Route::get('/suggestions',[AdController::class, 'suggestions']);

        //Offer or Ad
        Route::get('/category/{type}/{id}', [HomeController::class, 'showAdOrOffer']);

        //Favourite
        Route::post('/toggle-favourite', [FavouriteController::class, 'toggleFavourite']);
        Route::get('/user-favourite', [FavouriteController::class, 'userFavourite']);


        //Search
        Route::get('/search',[SearchController::class, 'search']);

        //Cart
        Route::get('/cart',[CartController::class, 'showCart']);
        Route::post('/cart/add',[CartController::class, 'addToCart']);
        Route::post('/cart/coupon',[CartController::class, 'addCoupon']);
        Route::post('/cart/remove',[CartController::class, 'removeFromCart']);
        Route::post('/cart/updateCount',[CartController::class, 'updateItemCount']);


        Route::get('/packaging-options', [PackagingOptionController::class, 'getPackagingOptions']);
        Route::post('/choose-options', [PackagingOptionController::class, 'chooseOptions']);

        //Addresses
        Route::resource('addresses', AddressController::class);

        //Delivery Companies
        Route::get('/delivery-companies', [DeliveryController::class, 'getCompanies']);

        //Order
        Route::post('/add-order', [OrderController::class, 'addOrder']);
        Route::get('/orders', [OrderController::class, 'getOrders']);
        Route::get('/orders/{id}', [OrderController::class, 'orderDetails']);

        //Payment
        Route::post('/process-payment', [PaymentController::class, 'processPayment']);
        Route::post('/payment/webhook/moyasar', [PaymentController::class, 'handleMoyasarWebhook']);
        Route::post('/payment/webhook/tabby', [PaymentController::class, 'handleTabbyWebhook']);


    });

});
