<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::get('/', [InventoryController::class, 'index'])->name('fabric.index');
Route::get('/search', [InventoryController::class, 'search'])->name('fabric.search');
