<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MazeInfoController;




Route::middleware(['authenticate_administration'])->group(function () {
    Route::put('update', [MazeInfoController::class, 'update']);
    Route::get('/',[MazeInfoController::class,'maze_info']);
});