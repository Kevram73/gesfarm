<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home.index');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate']);
    Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetLink'])->name('password.email');
});

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
    Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('home.profile');
    Route::put('/profile', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('home.profile.update');
    
    // Routes pour la ferme (une seule ferme)
    Route::get('/farm', [App\Http\Controllers\FarmController::class, 'index'])->name('farm.index');
    Route::get('/farm/edit', [App\Http\Controllers\FarmController::class, 'edit'])->name('farm.edit');
    Route::put('/farm', [App\Http\Controllers\FarmController::class, 'update'])->name('farm.update');
    
    // Routes pour les clients
    Route::resource('customers', App\Http\Controllers\CustomerController::class);

    // Routes pour les cultures
    Route::resource('crops', App\Http\Controllers\CropController::class);

    // Routes pour les champs
    Route::resource('fields', App\Http\Controllers\FieldController::class);

    // Routes pour les cultures dans les champs
    Route::resource('field-crops', App\Http\Controllers\FieldCropController::class);

    // Routes pour les récoltes
    Route::resource('harvests', App\Http\Controllers\HarvestController::class);

    // Routes pour les paiements
    Route::resource('payments', App\Http\Controllers\PaymentController::class);

    // Routes pour les employés
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);

    // Routes pour le bétail
    Route::resource('livestock', App\Http\Controllers\LivestockController::class);

    // Routes pour les équipements
    Route::resource('equipment', App\Http\Controllers\EquipmentController::class);

    // Routes pour l'inventaire
    Route::resource('inventory', App\Http\Controllers\InventoryController::class);

    // Routes pour les tâches
    Route::resource('farm-tasks', App\Http\Controllers\FarmTaskController::class);

    // Routes pour les options de sélection
    Route::resource('select-options', App\Http\Controllers\SelectOptionController::class)->except(['create', 'show', 'edit']);
    
    // Routes pour le module avicole
    Route::prefix('poultry')->name('poultry.')->group(function () {
        Route::resource('incubations', App\Http\Controllers\Poultry\EggIncubationController::class);
        Route::resource('chicks', App\Http\Controllers\Poultry\ChickController::class);
        Route::resource('prophylaxis', App\Http\Controllers\Poultry\ProphylaxisController::class);
        Route::post('prophylaxis/{id}/actions/{actionId}/complete', [App\Http\Controllers\Poultry\ProphylaxisController::class, 'completeAction'])->name('prophylaxis.actions.complete');
        Route::resource('feed', App\Http\Controllers\Poultry\PoultryFeedController::class);
        Route::resource('egg-production', App\Http\Controllers\Poultry\EggProductionController::class);
    });
    
    // Routes pour le module ruminants
    Route::prefix('livestock')->name('livestock.')->group(function () {
        Route::resource('breedings', App\Http\Controllers\Livestock\BreedingController::class);
        Route::resource('calvings', App\Http\Controllers\Livestock\CalvingController::class);
        Route::resource('milk-production', App\Http\Controllers\Livestock\MilkProductionController::class);
        Route::resource('health-records', App\Http\Controllers\Livestock\HealthRecordController::class);
    });
});

