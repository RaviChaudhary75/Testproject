<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/store', [ProfileController::class, 'store'])->name('profile.store');
Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::Put('/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/delete/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');


Route::get('/profiles/export/', [ProfileController::class, 'export'])->name('profiles.export');
