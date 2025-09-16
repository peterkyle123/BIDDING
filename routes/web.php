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
    $recentBiddings = Bidding::with('lgu')
        ->latest('created_at')
        ->take(5)
        ->get()
        ->map(function ($bidding) {
            $createdAt = \Carbon\Carbon::parse($bidding->created_at);

            return [
                'id'                 => $bidding->id,
                'project_name'       => $bidding->project_name,
                'abc'                => $bidding->abc,
                'pre_bid'            => $bidding->pre_bid,
                'prep_date'            => $bidding->prep_date,
                'bid_submission'     => $bidding->bid_submission,
                'bid_opening'        => $bidding->bid_opening,
                'reference_number'   => $bidding->reference_number,
                'solicitation_number'=> $bidding->solicitation_number,
                'delivery_schedule'  => $bidding->delivery_schedule,
                'category'           => $bidding->category,
                'lgu_id'             => $bidding->lgu_id,
                'lgu_name'           => $bidding->lgu->name ?? null,
                'lgu_envelope'       => $bidding->lgu->envelope_system ?? null,
                'created_at'         => $bidding->created_at->toDateTimeString(),
                'created_human'      => $createdAt->isFuture() ? 'just now' : $createdAt->diffForHumans(),
            ];
        });

    return response()->json(['data' => $recentBiddings]);
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
Route::post('/biddings/{bidding}/download-zip', [BiddingController::class, 'downloadZip'])
    ->name('biddings.downloadZip');