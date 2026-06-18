<?php

use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

Route::get('/holidays', [HolidayController::class, 'index']);
