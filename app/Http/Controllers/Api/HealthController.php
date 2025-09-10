<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Health Check
 * 
 * APIs for system health monitoring
 */
class HealthController extends Controller
{
    /**
     * Health Check
     * 
     * Check the health status of the API and database
     * 
     * @response 200 {
     *   "status": "healthy",
     *   "timestamp": "2024-01-20T10:30:00Z",
     *   "services": {
     *     "database": "connected",
     *     "cache": "connected"
     *   }
     * }
     */
    public function check()
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'services' => []
        ];

        // Check database connection
        try {
            DB::connection()->getPdo();
            $health['services']['database'] = 'connected';
        } catch (\Exception $e) {
            $health['services']['database'] = 'disconnected';
            $health['status'] = 'unhealthy';
        }

        // Check cache
        try {
            cache()->put('health_check', 'ok', 1);
            $health['services']['cache'] = cache()->get('health_check') === 'ok' ? 'connected' : 'disconnected';
        } catch (\Exception $e) {
            $health['services']['cache'] = 'disconnected';
            $health['status'] = 'unhealthy';
        }

        $statusCode = $health['status'] === 'healthy' ? 200 : 503;

        return response()->json($health, $statusCode);
    }

    /**
     * System Info
     * 
     * Get system information and statistics
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "version": "1.0.0",
     *     "environment": "production",
     *     "php_version": "8.1.0",
     *     "laravel_version": "10.0.0",
     *     "database_size": "15.2 MB",
     *     "total_users": 5,
     *     "total_records": 1250
     *   }
     * }
     */
    public function info()
    {
        $info = [
            'version' => '1.0.0',
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_size' => $this->getDatabaseSize(),
            'total_users' => \App\Models\User::count(),
            'total_records' => $this->getTotalRecords(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $info
        ]);
    }

    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.tables WHERE table_schema = ?", [$database]);
            return $result[0]->size_mb . ' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getTotalRecords()
    {
        $tables = [
            'poultry_flocks',
            'poultry_records',
            'cattle',
            'cattle_records',
            'crops',
            'crop_activities',
            'stock_items',
            'stock_movements',
            'tasks',
            'zones'
        ];

        $total = 0;
        foreach ($tables as $table) {
            try {
                $total += DB::table($table)->count();
            } catch (\Exception $e) {
                // Table might not exist yet
            }
        }

        return $total;
    }
}
