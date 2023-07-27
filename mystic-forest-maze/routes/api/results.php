<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultsController;




    Route::post('/', [ResultsController::class, 'create']);
    Route::get('/{timezone}', [ResultsController::class, 'get_results']);
    Route::get('/', [ResultsController::class, 'get_all_results']);









