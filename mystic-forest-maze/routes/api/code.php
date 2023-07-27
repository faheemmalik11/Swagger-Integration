<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodeController;




Route::middleware(['authenticate_administration'])->group(function () {
    Route::post('create', [CodeController::class, 'create']);
    Route::put('update', [CodeController::class, 'update']);
    Route::get('/', [CodeController::class, 'code']);
    Route::get('all',[CodeController::class,'code_list']);
    Route::delete('delete/{id}',[CodeController::class, 'delete']);
});

Route::post("verify",[CodeController::class, 'verify']);







