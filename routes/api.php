<?php

use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\MachineTypesController;
use App\Http\Controllers\MachineController;
use App\Models\MachineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


