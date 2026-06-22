<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\TourPackageController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ArticleController;

/*
|--------------------------------------------------------------------------
| Public API Routes (Visitor)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{destination}', [DestinationController::class, 'show']);

Route::get('/packages', [TourPackageController::class, 'index']);
Route::get('/packages/{package}', [TourPackageController::class, 'show']);

Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{booking}', [BookingController::class, 'show']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::post('/events/{event}/register', [EventController::class, 'register']);

Route::get('/testimonials', [TestimonialController::class, 'index']);

Route::post('/subscribers', [SubscriberController::class, 'store']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (Admin Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Categories CRUD
    Route::apiResource('admin/categories', CategoryController::class);

    // Destinations CRUD (We use POST instead of PUT/PATCH for updates to support multipart/form-data with file uploads)
    Route::get('admin/destinations', [DestinationController::class, 'index']);
    Route::post('admin/destinations', [DestinationController::class, 'store']);
    Route::get('admin/destinations/{id}', [DestinationController::class, 'show']);
    Route::post('admin/destinations/{id}', [DestinationController::class, 'update']);
    Route::delete('admin/destinations/{id}', [DestinationController::class, 'destroy']);

    // Packages CRUD
    Route::get('admin/packages', [TourPackageController::class, 'index']);
    Route::post('admin/packages', [TourPackageController::class, 'store']);
    Route::get('admin/packages/{id}', [TourPackageController::class, 'show']);
    Route::post('admin/packages/{id}', [TourPackageController::class, 'update']);
    Route::delete('admin/packages/{id}', [TourPackageController::class, 'destroy']);

    // Testimonials CRUD
    Route::get('admin/testimonials', [TestimonialController::class, 'index']);
    Route::post('admin/testimonials', [TestimonialController::class, 'store']);
    Route::get('admin/testimonials/{id}', [TestimonialController::class, 'show']);
    Route::post('admin/testimonials/{id}', [TestimonialController::class, 'update']);
    Route::delete('admin/testimonials/{id}', [TestimonialController::class, 'destroy']);

    // Events CRUD
    Route::get('admin/events', [EventController::class, 'index']);
    Route::post('admin/events', [EventController::class, 'store']);
    Route::get('admin/events/{id}', [EventController::class, 'show']);
    Route::post('admin/events/{id}', [EventController::class, 'update']);
    Route::delete('admin/events/{id}', [EventController::class, 'destroy']);

    // Articles CRUD & Gallery
    Route::get('admin/articles', [ArticleController::class, 'index']);
    Route::post('admin/articles', [ArticleController::class, 'store']);
    Route::get('admin/articles/{id}', [ArticleController::class, 'show']);
    Route::post('admin/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('admin/articles/{id}', [ArticleController::class, 'destroy']);
    Route::delete('admin/articles/gallery/{id}', [ArticleController::class, 'destroyGalleryImage']);

    // Bookings Admin
    Route::get('admin/bookings', [BookingController::class, 'index']);
    Route::put('admin/bookings/{id}/status', [BookingController::class, 'updateStatus']);
    Route::delete('admin/bookings/{id}', [BookingController::class, 'destroy']);

    // Subscribers Admin
    Route::get('admin/subscribers', [SubscriberController::class, 'index']);
    Route::delete('admin/subscribers/{id}', [SubscriberController::class, 'destroy']);
    Route::get('admin/subscribers-export', [SubscriberController::class, 'export']);

    // Registrations Admin
    Route::get('admin/registrations', [EventController::class, 'registrations']);
    Route::delete('admin/registrations/{id}', [EventController::class, 'destroyRegistration']);
    Route::get('admin/registrations-export', [EventController::class, 'exportRegistrations']);
});



