<?php

use App\Http\Controllers\EntryController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Simple Login/Logout (For demonstration purposes in this test)
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect('/dashboard');
    }
    return back()->withErrors(['email' => 'Invalid credentials']);
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
            return view('dashboard');
        }
        )->name('dashboard');

        Route::apiResource('api/entries', EntryController::class)->only(['index', 'show']);

        Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
            Route::apiResource('api/entries', EntryController::class)->only(['store', 'update', 'destroy']);
        }
        );

        Route::middleware([RoleMiddleware::class . ':staff'])->group(function () {
            Route::post('/api/entries/{entry}/image', [EntryController::class , 'updateImage']);
        }
        );
    });
