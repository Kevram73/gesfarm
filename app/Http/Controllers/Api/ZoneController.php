<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Zone Management
 * 
 * APIs for managing farm zones and cartography
 */
class ZoneController extends Controller
{
    /**
     * Get Zones
     * 
     * Retrieve a paginated list of zones
     * 
     * @queryParam type string Filter by zone type (cultivation, pasture, enclosure, building, water_point)
     * @queryParam status string Filter by status (active, inactive, maintenance)
     * @queryParam search string Search in name and description
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "zones": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Zone::class)
            ->allowedFilters(['type', 'status', 'name'])
            ->allowedSorts(['name', 'area', 'created_at']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $zones = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'zones' => $zones->items(),
                'pagination' => [
                    'current_page' => $zones->currentPage(),
                    'last_page' => $zones->lastPage(),
                    'per_page' => $zones->perPage(),
                    'total' => $zones->total()
                ]
            ]
        ]);
    }

    /**
     * Create Zone
     * 
     * Create a new zone
     * 
     * @bodyParam name string required Zone name
     * @bodyParam description string Zone description
     * @bodyParam type string required Zone type (cultivation, pasture, enclosure, building, water_point)
     * @bodyParam coordinates json required GeoJSON coordinates
     * @bodyParam area numeric Zone area in square meters
     * @bodyParam status string Zone status (active, inactive, maintenance)
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Zone created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:cultivation,pasture,enclosure,building,water_point',
            'coordinates' => 'required|array',
            'area' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:active,inactive,maintenance',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $zone = Zone::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Zone created successfully',
            'data' => $zone
        ], 201);
    }

    /**
     * Get Zone Details
     * 
     * Retrieve a specific zone with its associated data
     * 
     * @urlParam id integer required Zone ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $zone = Zone::with(['poultryFlocks', 'cattle', 'crops', 'tasks'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $zone
        ]);
    }

    /**
     * Update Zone
     * 
     * Update an existing zone
     * 
     * @urlParam id integer required Zone ID
     * @bodyParam name string Zone name
     * @bodyParam description string Zone description
     * @bodyParam type string Zone type
     * @bodyParam coordinates json GeoJSON coordinates
     * @bodyParam area numeric Zone area in square meters
     * @bodyParam status string Zone status
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Zone updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $zone = Zone::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:cultivation,pasture,enclosure,building,water_point',
            'coordinates' => 'sometimes|array',
            'area' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:active,inactive,maintenance',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $zone->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Zone updated successfully',
            'data' => $zone
        ]);
    }

    /**
     * Delete Zone
     * 
     * Delete a zone
     * 
     * @urlParam id integer required Zone ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Zone deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        
        // Check if zone has associated data
        if ($zone->poultryFlocks()->count() > 0 || 
            $zone->cattle()->count() > 0 || 
            $zone->crops()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete zone with associated data'
            ], 400);
        }

        $zone->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Zone deleted successfully'
        ]);
    }

    /**
     * Get Zone Statistics
     * 
     * Get statistics for a specific zone
     * 
     * @urlParam id integer required Zone ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "zone": {...},
     *     "statistics": {
     *       "poultry_count": 0,
     *       "cattle_count": 0,
     *       "crops_count": 0,
     *       "active_tasks": 0
     *     }
     *   }
     * }
     */
    public function statistics($id)
    {
        $zone = Zone::findOrFail($id);

        $statistics = [
            'poultry_count' => $zone->poultryFlocks()->where('status', 'active')->count(),
            'cattle_count' => $zone->cattle()->where('status', 'active')->count(),
            'crops_count' => $zone->crops()->where('status', '!=', 'harvested')->count(),
            'active_tasks' => $zone->tasks()->where('status', '!=', 'completed')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'zone' => $zone,
                'statistics' => $statistics
            ]
        ]);
    }
}
