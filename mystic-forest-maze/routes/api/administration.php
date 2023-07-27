<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdministrationController;


    Route::post('login', [AdministrationController::class, 'login']);


Route::middleware(['authenticate_administration'])->group(function () {
    Route::get('logout', [AdministrationController::class, 'logout']);
    Route::put('update', [AdministrationController::class, 'update']);
    Route::put('resetPassword', [AdministrationController::class, 'resetPassword']);

});







