<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\CropActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Crop Management
 * 
 * APIs for managing crops and agricultural activities
 */
class CropController extends Controller
{
    /**
     * Get Crops
     * 
     * Retrieve a paginated list of crops
     * 
     * @queryParam name string Filter by crop name
     * @queryParam variety string Filter by variety
     * @queryParam zone_id integer Filter by zone ID
     * @queryParam status string Filter by status (planted, growing, harvested, failed)
     * @queryParam search string Search in name and variety
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "crops": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Crop::class)
            ->with(['zone', 'activities'])
            ->allowedFilters(['name', 'variety', 'zone_id', 'status'])
            ->allowedSorts(['name', 'planting_date', 'expected_harvest_date']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('variety', 'like', "%{$search}%");
            });
        }

        $crops = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'crops' => $crops->items(),
                'pagination' => [
                    'current_page' => $crops->currentPage(),
                    'last_page' => $crops->lastPage(),
                    'per_page' => $crops->perPage(),
                    'total' => $crops->total()
                ]
            ]
        ]);
    }

    /**
     * Create Crop
     * 
     * Create a new crop record
     * 
     * @bodyParam name string required Crop name
     * @bodyParam variety string required Crop variety
     * @bodyParam zone_id integer required Zone ID
     * @bodyParam planting_date date required Planting date
     * @bodyParam expected_harvest_date date Expected harvest date
     * @bodyParam planted_area numeric required Planted area in square meters
     * @bodyParam expected_yield numeric Expected yield in kg
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Crop created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'variety' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,id',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'nullable|date|after:planting_date',
            'planted_area' => 'required|numeric|min:0.01',
            'expected_yield' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $crop = Crop::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Crop created successfully',
            'data' => $crop->load('zone')
        ], 201);
    }

    /**
     * Get Crop Details
     * 
     * Retrieve a specific crop with its activities
     * 
     * @urlParam id integer required Crop ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $crop = Crop::with(['zone', 'activities.performedBy'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $crop
        ]);
    }

    /**
     * Update Crop
     * 
     * Update an existing crop record
     * 
     * @urlParam id integer required Crop ID
     * @bodyParam name string Crop name
     * @bodyParam variety string Crop variety
     * @bodyParam zone_id integer Zone ID
     * @bodyParam expected_harvest_date date Expected harvest date
     * @bodyParam actual_harvest_date date Actual harvest date
     * @bodyParam planted_area numeric Planted area in square meters
     * @bodyParam expected_yield numeric Expected yield in kg
     * @bodyParam actual_yield numeric Actual yield in kg
     * @bodyParam status string Status
     * @bodyParam notes string Additional notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Crop updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $crop = Crop::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'variety' => 'sometimes|string|max:255',
            'zone_id' => 'sometimes|exists:zones,id',
            'expected_harvest_date' => 'nullable|date|after:planting_date',
            'actual_harvest_date' => 'nullable|date|after:planting_date',
            'planted_area' => 'sometimes|numeric|min:0.01',
            'expected_yield' => 'nullable|numeric|min:0',
            'actual_yield' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:planted,growing,harvested,failed',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $crop->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Crop updated successfully',
            'data' => $crop->load('zone')
        ]);
    }

    /**
     * Record Crop Activity
     * 
     * Record an agricultural activity for a crop
     * 
     * @bodyParam crop_id integer required Crop ID
     * @bodyParam activity_type string required Activity type (planting, fertilizing, irrigation, pest_control, harvesting)
     * @bodyParam activity_date date required Activity date
     * @bodyParam description string required Activity description
     * @bodyParam materials_used json Materials used (array of stock items)
     * @bodyParam cost numeric Activity cost
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Crop activity recorded successfully",
     *   "data": {...}
     * }
     */
    public function recordActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crop_id' => 'required|exists:crops,id',
            'activity_type' => 'required|in:planting,fertilizing,irrigation,pest_control,harvesting',
            'activity_date' => 'required|date',
            'description' => 'required|string',
            'materials_used' => 'nullable|array',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['performed_by'] = $request->user()->id;

        $activity = CropActivity::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Crop activity recorded successfully',
            'data' => $activity->load(['crop', 'performedBy'])
        ], 201);
    }

    /**
     * Get Crop Activities
     * 
     * Retrieve activities for a specific crop
     * 
     * @urlParam id integer required Crop ID
     * @queryParam activity_type string Filter by activity type
     * @queryParam start_date date Filter activities from date
     * @queryParam end_date date Filter activities to date
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "activities": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function activities($id, Request $request)
    {
        $crop = Crop::findOrFail($id);

        $query = QueryBuilder::for(CropActivity::class)
            ->where('crop_id', $id)
            ->with('performedBy')
            ->allowedFilters(['activity_type', 'activity_date'])
            ->allowedSorts(['activity_date', 'created_at']);

        if ($request->has('start_date')) {
            $query->where('activity_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('activity_date', '<=', $request->end_date);
        }

        $activities = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'crop' => $crop,
                'activities' => $activities->items(),
                'pagination' => [
                    'current_page' => $activities->currentPage(),
                    'last_page' => $activities->lastPage(),
                    'per_page' => $activities->perPage(),
                    'total' => $activities->total()
                ]
            ]
        ]);
    }
}
