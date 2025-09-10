<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PoultryFlock;
use App\Models\PoultryRecord;
use App\Models\IncubationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Poultry Management
 * 
 * APIs for managing poultry flocks, records, and incubation
 */
class PoultryController extends Controller
{
    /**
     * Get Poultry Flocks
     * 
     * Retrieve a paginated list of poultry flocks
     * 
     * @queryParam type string Filter by flock type (layer, broiler, duck, turkey)
     * @queryParam status string Filter by status (active, sold, deceased)
     * @queryParam zone_id integer Filter by zone ID
     * @queryParam search string Search in flock number and breed
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "flocks": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(PoultryFlock::class)
            ->with(['zone', 'records'])
            ->allowedFilters(['type', 'status', 'zone_id', 'breed'])
            ->allowedSorts(['flock_number', 'arrival_date', 'current_quantity']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('flock_number', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }

        $flocks = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'flocks' => $flocks->items(),
                'pagination' => [
                    'current_page' => $flocks->currentPage(),
                    'last_page' => $flocks->lastPage(),
                    'per_page' => $flocks->perPage(),
                    'total' => $flocks->total()
                ]
            ]
        ]);
    }

    /**
     * Create Poultry Flock
     * 
     * Create a new poultry flock
     * 
     * @bodyParam flock_number string required Unique flock number
     * @bodyParam type string required Flock type (layer, broiler, duck, turkey)
     * @bodyParam breed string required Breed name
     * @bodyParam initial_quantity integer required Initial number of birds
     * @bodyParam arrival_date date required Arrival date
     * @bodyParam zone_id integer Zone ID
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Poultry flock created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flock_number' => 'required|string|unique:poultry_flocks,flock_number',
            'type' => 'required|in:layer,broiler,duck,turkey',
            'breed' => 'required|string|max:255',
            'initial_quantity' => 'required|integer|min:1',
            'arrival_date' => 'required|date',
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

        $data = $request->all();
        $data['current_quantity'] = $data['initial_quantity'];
        $data['age_days'] = now()->diffInDays($request->arrival_date);

        $flock = PoultryFlock::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Poultry flock created successfully',
            'data' => $flock->load('zone')
        ], 201);
    }

    /**
     * Get Poultry Flock
     * 
     * Retrieve a specific poultry flock with its records
     * 
     * @urlParam id integer required Flock ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $flock = PoultryFlock::with(['zone', 'records.recordedBy'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $flock
        ]);
    }

    /**
     * Update Poultry Flock
     * 
     * Update an existing poultry flock
     * 
     * @urlParam id integer required Flock ID
     * @bodyParam flock_number string Unique flock number
     * @bodyParam type string Flock type
     * @bodyParam breed string Breed name
     * @bodyParam current_quantity integer Current number of birds
     * @bodyParam zone_id integer Zone ID
     * @bodyParam status string Status
     * @bodyParam notes string Additional notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Poultry flock updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $flock = PoultryFlock::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'flock_number' => 'sometimes|string|unique:poultry_flocks,flock_number,' . $id,
            'type' => 'sometimes|in:layer,broiler,duck,turkey',
            'breed' => 'sometimes|string|max:255',
            'current_quantity' => 'sometimes|integer|min:0',
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

        $flock->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Poultry flock updated successfully',
            'data' => $flock->load('zone')
        ]);
    }

    /**
     * Record Poultry Data
     * 
     * Record daily poultry data (eggs, feed, mortality, etc.)
     * 
     * @bodyParam flock_id integer required Flock ID
     * @bodyParam record_date date required Record date
     * @bodyParam eggs_collected integer Number of eggs collected
     * @bodyParam feed_consumed numeric Feed consumed in kg
     * @bodyParam mortality_count integer Number of deaths
     * @bodyParam average_weight numeric Average weight in kg
     * @bodyParam health_notes string Health observations
     * @bodyParam observations string General observations
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Poultry record created successfully",
     *   "data": {...}
     * }
     */
    public function recordData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flock_id' => 'required|exists:poultry_flocks,id',
            'record_date' => 'required|date',
            'eggs_collected' => 'nullable|integer|min:0',
            'feed_consumed' => 'nullable|numeric|min:0',
            'mortality_count' => 'nullable|integer|min:0',
            'average_weight' => 'nullable|numeric|min:0',
            'health_notes' => 'nullable|string',
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

        $record = PoultryRecord::create($data);

        // Update flock current quantity if mortality recorded
        if ($request->has('mortality_count') && $request->mortality_count > 0) {
            $flock = PoultryFlock::find($request->flock_id);
            $flock->decrement('current_quantity', $request->mortality_count);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Poultry record created successfully',
            'data' => $record->load(['flock', 'recordedBy'])
        ], 201);
    }

    /**
     * Get Incubation Records
     * 
     * Retrieve incubation records
     * 
     * @queryParam egg_type string Filter by egg type
     * @queryParam breed string Filter by breed
     * @queryParam start_date date Filter by start date
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "records": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function incubationRecords(Request $request)
    {
        $query = QueryBuilder::for(IncubationRecord::class)
            ->with('recordedBy')
            ->allowedFilters(['egg_type', 'breed', 'start_date'])
            ->allowedSorts(['start_date', 'hatch_rate', 'created_at']);

        $records = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'records' => $records->items(),
                'pagination' => [
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                    'per_page' => $records->perPage(),
                    'total' => $records->total()
                ]
            ]
        ]);
    }

    /**
     * Create Incubation Record
     * 
     * Create a new incubation record
     * 
     * @bodyParam batch_number string required Unique batch number
     * @bodyParam egg_type string required Egg type (chicken, duck, turkey)
     * @bodyParam breed string required Breed name
     * @bodyParam egg_count integer required Number of eggs
     * @bodyParam start_date date required Incubation start date
     * @bodyParam incubation_days integer required Incubation period in days
     * @bodyParam temperature numeric required Temperature in Celsius
     * @bodyParam humidity_percentage numeric required Humidity percentage
     * @bodyParam egg_size string required Egg size (small, medium, large)
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Incubation record created successfully",
     *   "data": {...}
     * }
     */
    public function createIncubationRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_number' => 'required|string|unique:incubation_records,batch_number',
            'egg_type' => 'required|in:chicken,duck,turkey',
            'breed' => 'required|string|max:255',
            'egg_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'incubation_days' => 'required|integer|min:1',
            'temperature' => 'required|numeric|min:0',
            'humidity_percentage' => 'required|numeric|min:0|max:100',
            'egg_size' => 'required|in:small,medium,large',
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
        $data['recorded_by'] = $request->user()->id;

        $record = IncubationRecord::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Incubation record created successfully',
            'data' => $record->load('recordedBy')
        ], 201);
    }

    /**
     * Update Incubation Results
     * 
     * Update incubation record with hatching results
     * 
     * @urlParam id integer required Incubation record ID
     * @bodyParam hatched_count integer Number of hatched eggs
     * @bodyParam unhatched_count integer Number of unhatched eggs
     * @bodyParam notes string Additional notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Incubation results updated successfully",
     *   "data": {...}
     * }
     */
    public function updateIncubationResults(Request $request, $id)
    {
        $record = IncubationRecord::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'hatched_count' => 'required|integer|min:0',
            'unhatched_count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $hatchedCount = $request->hatched_count;
        $unhatchedCount = $request->unhatched_count;
        $totalEggs = $hatchedCount + $unhatchedCount;

        if ($totalEggs > $record->egg_count) {
            return response()->json([
                'status' => 'error',
                'message' => 'Total hatched and unhatched eggs cannot exceed original egg count'
            ], 400);
        }

        $hatchRate = $totalEggs > 0 ? ($hatchedCount / $totalEggs) * 100 : 0;

        $record->update([
            'hatched_count' => $hatchedCount,
            'unhatched_count' => $unhatchedCount,
            'hatch_rate' => $hatchRate,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Incubation results updated successfully',
            'data' => $record->load('recordedBy')
        ]);
    }
}
