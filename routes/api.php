<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/data-1', [ApiController::class, 'getDataFirst']);
Route::get('/data-2', [ApiController::class, 'getDataSecond']);
Route::get('/sample-1', [ApiController::class, 'getSampleFirst']);
Route::get('/sample-2', [ApiController::class, 'getSampleSecond']);
Route::get('/result-1', [ApiController::class, 'manipulateJson']);
Route::get('/result-2', [ApiController::class, 'sortedJsonByDistance']);
