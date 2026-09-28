<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronBuilderController;

Route::get('/', [CronBuilderController::class, 'index']);

Route::post('/calculate-cron', [CronBuilderController::class, 'calculate']);