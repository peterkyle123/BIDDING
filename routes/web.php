<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LGUController; // <-- add this
use App\Http\Controllers\BiddingController;
use App\Models\Bidding;
use Carbon\Carbon;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', function () {
    $features = [
        ['title' => 'Feature 1', 'description' => 'Description of feature 1.'],
        ['title' => 'Feature 2', 'description' => 'Description of feature 2.'],
        ['title' => 'Feature 3', 'description' => 'Description of feature 3.'],
    ];

    $recentBiddings = Bidding::where('created_at', '>=', Carbon::now()->subMinutes(5))
        ->latest()
        ->get();

    return view('home', compact('features', 'recentBiddings'));
})->name('home');

Route::get('/lgus', [LGUController::class, 'index'])->name('lgus.index');
Route::post('/lgus', [LGUController::class, 'store'])->name('lgus.store');
Route::put('/lgus/{lgu}', [LGUController::class, 'update'])->name('lgus.update');
Route::delete('/lgus/{lgu}', [LGUController::class, 'destroy'])->name('lgus.destroy');
Route::resource('lgus', LGUController::class);
Route::resource('biddings', BiddingController::class);
Route::get('/recent-biddings', function () {
    $recentBiddings = Bidding::where('created_at', '>=', now()->subMinutes(5))
        ->latest()
        ->get();

    return response()->json($recentBiddings);
});
// Documents routes
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
Route::get('/documents/{document}/edit-word', [DocumentController::class, 'editWord'])
     ->name('documents.editWord');

// routes/web.php
Route::get('/biddings/{bidding}/generate/{document}', [DocumentController::class, 'generateFromTemplate'])
    ->name('biddings.generate');
