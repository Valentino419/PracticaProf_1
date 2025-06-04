<?php

use App\Http\Controllers\JobSiteController;
use App\Http\Controllers\JobSiteMachineController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\MachineTypesController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserController;

use App\Models\Machine;
use App\Models\MachineType;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    return view('index');
});

//Province
Route::resource('jobsites',JobSiteController::class);
Route::resource('provinces',ProvinceController::class);

Route::get('/maintenances/filter', [MaintenanceController::class, 'filter'])->name('maintenances.filter');
Route::resource('maintenances',MaintenanceController::class);

//Route::get('/provinces/index', [ProvinceController::class, 'index']);
// machine type 
//Route::get('/machinetypes/{id}/getMachines',[MachineTypesController::class, 'getMachines']);//->name('MachineType.machines');
Route::resource('machinetypes',MachineTypesController::class);

//machine 
Route::get('/machines/filter', [MaintenanceController::class, 'filter'])->name('machines.filter');
Route::get('/machines/{id}/currentProvince',[MachineController::class,'currentProvince'])->name('machines.currentProvince');
Route::get('/machines/{id}/currentJobsite',[MachineController::class,'currentJobsite'])->name('machines.currentJobsite');
Route::get('/machines/{id}/maintenances',[MachineController::class,'getMaintenances'])->name('machines.maintenances');
Route::get('/machines/{id}/kilometers',[MachineController::class,'kilometers'])->name('machines.kilometers');
Route::resource('machines',MachineController::class);
Route::post('jobsitemachines/{id}/activate', [JobsiteMachineController::class, 'activate'])->name('jobsitemachines.activate');
route::get('jobsitemachines/{id}/conclude',[JobSiteMachineController::class,'conclution'])->name('jobsitemachines.conclude');
route::resource('jobsitemachines',JobSiteMachineController::class);
