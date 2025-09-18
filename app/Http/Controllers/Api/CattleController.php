<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cattle;
use App\Models\CattleRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Cattle Management
 * 
 * APIs for managing cattle and their records
 */
class CattleController extends Controller
{
    /**
     * Get Cattle
     * 
     * Retrieve a paginated list of cattle
     * 
     * @queryParam breed string Filter by breed
     * @queryParam gender string Filter by gender (male, female)
     * @queryParam status string Filter by status (active, sold, deceased)
     * @queryParam zone_id integer Filter by zone ID
     * @queryParam search string Search in tag number and name
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "cattle": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Cattle::class)
            ->with(['zone', 'records'])
            ->allowedFilters(['breed', 'gender', 'status', 'zone_id'])
            ->allowedSorts(['tag_number', 'birth_date', 'current_weight']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('tag_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $cattle = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'cattle' => $cattle->items(),
                'pagination' => [
                    'current_page' => $cattle->currentPage(),
                    'last_page' => $cattle->lastPage(),
                    'per_page' => $cattle->perPage(),
                    'total' => $cattle->total()
                ]
            ]
        ]);
    }

    /**
     * Create Cattle
     * 
     * Create a new cattle record
     * 
     * @bodyParam tag_number string required Unique tag number
     * @bodyParam name string Cattle name
     * @bodyParam breed string required Breed name
     * @bodyParam gender string required Gender (male, female)
     * @bodyParam birth_date date required Birth date
     * @bodyParam mother_tag string Mother's tag number
     * @bodyParam father_tag string Father's tag number
     * @bodyParam current_weight numeric Current weight in kg
     * @bodyParam zone_id integer Zone ID
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Cattle created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_number' => 'required|string|unique:cattle,tag_number',
            'name' => 'nullable|string|max:255',
            'breed' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'mother_tag' => 'nullable|string|max:255',
            'father_tag' => 'nullable|string|max:255',
            'current_weight' => 'nullable|numeric|min:0',
            'zone_id' => 'nullable|exists:zones,id',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cattle = Cattle::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cattle created successfully',
            'data' => $cattle->load('zone')
        ], 201);
    }

    /**
     * Get Cattle Details
     * 
     * Retrieve a specific cattle with its records
     * 
     * @urlParam id integer required Cattle ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $cattle = Cattle::with(['zone', 'records.recordedBy'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $cattle
        ]);
    }

    /**
     * Update Cattle
     * 
     * Update an existing cattle record
     * 
     * @urlParam id integer required Cattle ID
     * @bodyParam tag_number string Unique tag number
     * @bodyParam name string Cattle name
     * @bodyParam breed string Breed name
     * @bodyParam gender string Gender
     * @bodyParam current_weight numeric Current weight in kg
     * @bodyParam zone_id integer Zone ID
     * @bodyParam status string Status
     * @bodyParam notes string Additional notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Cattle updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $cattle = Cattle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tag_number' => 'sometimes|string|unique:cattle,tag_number,' . $id,
            'name' => 'nullable|string|max:255',
            'breed' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female',
            'current_weight' => 'nullable|numeric|min:0',
            'zone_id' => 'nullable|exists:zones,id',
            'status' => 'sometimes|in:active,sold,deceased',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cattle->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cattle updated successfully',
            'data' => $cattle->load('zone')
        ]);
    }

    /**
     * Record Cattle Data
     * 
     * Record daily cattle data (milk production, weight, health, etc.)
     * 
     * @bodyParam cattle_id integer required Cattle ID
     * @bodyParam record_date date required Record date
     * @bodyParam milk_production numeric Milk production in liters
     * @bodyParam weight numeric Weight in kg
     * @bodyParam health_status string Health status (healthy, sick, treated)
     * @bodyParam health_notes string Health observations
     * @bodyParam feeding_notes string Feeding observations
     * @bodyParam observations string General observations
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Cattle record created successfully",
     *   "data": {...}
     * }
     */
    public function recordData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cattle_id' => 'required|exists:cattle,id',
            'record_date' => 'required|date',
            'milk_production' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'health_status' => 'nullable|in:healthy,sick,treated',
            'health_notes' => 'nullable|string',
            'feeding_notes' => 'nullable|string',
            'observations' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['recorded_by'] = $request->user()->id;

        $record = CattleRecord::create($data);

        // Update cattle weight if provided
        if ($request->has('weight') && $request->weight > 0) {
            $cattle = Cattle::find($request->cattle_id);
            $cattle->update(['current_weight' => $request->weight]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cattle record created successfully',
            'data' => $record->load(['cattle', 'recordedBy'])
        ], 201);
    }

    /**
     * Delete Cattle
     * 
     * Delete a cattle record
     * 
     * @urlParam cattle integer required Cattle ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Cattle deleted successfully"
     * }
     * 
     * @response 404 {
     *   "status": "error",
     *   "message": "Cattle not found"
     * }
     */
    public function destroy($id)
    {
        $cattle = Cattle::find($id);

        if (!$cattle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cattle not found'
            ], 404);
        }

        // Delete associated records first
        $cattle->records()->delete();
        
        // Delete the cattle
        $cattle->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cattle deleted successfully'
        ], 200);
    }
}
