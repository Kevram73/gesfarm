<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\StockCategoryController;
use App\Http\Controllers\Api\PoultryController;
use App\Http\Controllers\Api\CattleController;
use App\Http\Controllers\Api\CropController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FinancialController;
use App\Http\Controllers\Api\VeterinaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/health', [HealthController::class, 'check']);
Route::get('/info', [HealthController::class, 'info']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stock-alerts', [DashboardController::class, 'stockAlerts']);
    Route::get('/dashboard/poultry-stats', [DashboardController::class, 'poultryStats']);
    Route::get('/dashboard/cattle-stats', [DashboardController::class, 'cattleStats']);
    Route::get('/dashboard/crop-stats', [DashboardController::class, 'cropStats']);
    Route::get('/dashboard/task-stats', [DashboardController::class, 'taskStats']);
    
    // User management routes
    Route::apiResource('users', UserController::class);
    Route::get('/roles', [UserController::class, 'roles']);
    Route::get('/permissions', [UserController::class, 'permissions']);
    
    // Stock management routes
    Route::apiResource('stock/categories', StockCategoryController::class);
    Route::apiResource('stock/items', StockController::class);
    Route::post('/stock/movements', [StockController::class, 'recordMovement']);
    
    // Poultry management routes
    Route::apiResource('poultry/flocks', PoultryController::class);
    Route::post('/poultry/records', [PoultryController::class, 'recordData']);
    Route::get('/poultry/incubation', [PoultryController::class, 'incubationRecords']);
    Route::post('/poultry/incubation', [PoultryController::class, 'createIncubationRecord']);
    Route::put('/poultry/incubation/{id}/results', [PoultryController::class, 'updateIncubationResults']);
    
    // Cattle management routes
    Route::apiResource('cattle', CattleController::class);
    Route::post('/cattle/records', [CattleController::class, 'recordData']);
    
    // Crop management routes
    Route::apiResource('crops', CropController::class);
    Route::post('/crops/activities', [CropController::class, 'recordActivity']);
    Route::get('/crops/{id}/activities', [CropController::class, 'activities']);
    
    // Zone management routes
    Route::apiResource('zones', ZoneController::class);
    Route::get('/zones/{id}/statistics', [ZoneController::class, 'statistics']);
    
    // Task management routes
    Route::apiResource('tasks', TaskController::class);
    Route::get('/my-tasks', [TaskController::class, 'myTasks']);
    Route::put('/tasks/{id}/complete', [TaskController::class, 'complete']);
    
    // Reports routes
    Route::prefix('reports')->group(function () {
        Route::get('/poultry-production', [ReportController::class, 'poultryProduction']);
        Route::get('/cattle-production', [ReportController::class, 'cattleProduction']);
        Route::get('/stock-movements', [ReportController::class, 'stockMovements']);
        Route::get('/crop-performance', [ReportController::class, 'cropPerformance']);
        Route::get('/financial-summary', [ReportController::class, 'financialSummary']);
    });
    
    // Analytics routes
    Route::prefix('analytics')->group(function () {
        Route::get('/poultry', [AnalyticsController::class, 'getPoultryAnalytics']);
        Route::get('/cattle', [AnalyticsController::class, 'getCattleAnalytics']);
        Route::get('/crops', [AnalyticsController::class, 'getCropAnalytics']);
        Route::get('/farm-overview', [AnalyticsController::class, 'getFarmOverview']);
    });
    
    // Financial management routes
    Route::prefix('financial')->group(function () {
        Route::get('/transactions', [FinancialController::class, 'getTransactions']);
        Route::post('/transactions', [FinancialController::class, 'createTransaction']);
        Route::get('/transactions/{transaction}', [FinancialController::class, 'getTransaction']);
        Route::put('/transactions/{transaction}', [FinancialController::class, 'updateTransaction']);
        Route::delete('/transactions/{transaction}', [FinancialController::class, 'deleteTransaction']);
        
        Route::get('/budgets', [FinancialController::class, 'getBudgets']);
        Route::post('/budgets', [FinancialController::class, 'createBudget']);
        
        Route::get('/reports', [FinancialController::class, 'getFinancialReports']);
    });
    
    // Veterinary management routes
    Route::prefix('veterinary')->group(function () {
        Route::get('/treatments', [VeterinaryController::class, 'getTreatments']);
        Route::post('/treatments', [VeterinaryController::class, 'createTreatment']);
        Route::get('/treatments/{treatment}', [VeterinaryController::class, 'getTreatment']);
        Route::put('/treatments/{treatment}', [VeterinaryController::class, 'updateTreatment']);
        Route::delete('/treatments/{treatment}', [VeterinaryController::class, 'deleteTreatment']);
        
        Route::get('/schedule', [VeterinaryController::class, 'getSchedule']);
        Route::get('/reminders', [VeterinaryController::class, 'getReminders']);
        Route::get('/animal-history', [VeterinaryController::class, 'getAnimalHistory']);
        Route::get('/stats', [VeterinaryController::class, 'getVeterinaryStats']);
    });
    
    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::get('/unread', [NotificationController::class, 'getUnreadNotifications']);
        Route::post('/', [NotificationController::class, 'createNotification']);
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'deleteNotification']);
        Route::get('/stats', [NotificationController::class, 'getNotificationStats']);
    });
});
