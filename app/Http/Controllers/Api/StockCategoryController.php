<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Stock Categories
 * 
 * APIs for managing stock categories
 */
class StockCategoryController extends Controller
{
    /**
     * Get Stock Categories
     * 
     * Retrieve a list of stock categories
     * 
     * @queryParam type string Filter by category type
     * @queryParam search string Search in name and description
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": [...]
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(StockCategory::class)
            ->withCount('stockItems')
            ->allowedFilters(['type', 'name'])
            ->allowedSorts(['name', 'type', 'created_at']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Create Stock Category
     * 
     * Create a new stock category
     * 
     * @bodyParam name string required Category name
     * @bodyParam description string Category description
     * @bodyParam type string required Category type (agricultural_inputs, animal_feed, equipment, veterinary_products)
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Stock category created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:agricultural_inputs,animal_feed,equipment,veterinary_products',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = StockCategory::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Stock category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Get Stock Category Details
     * 
     * Retrieve a specific stock category with its items
     * 
     * @urlParam id integer required Category ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $category = StockCategory::with(['stockItems' => function ($query) {
            $query->latest()->limit(10);
        }])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Update Stock Category
     * 
     * Update an existing stock category
     * 
     * @urlParam id integer required Category ID
     * @bodyParam name string Category name
     * @bodyParam description string Category description
     * @bodyParam type string Category type
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Stock category updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $category = StockCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:agricultural_inputs,animal_feed,equipment,veterinary_products',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Stock category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Delete Stock Category
     * 
     * Delete a stock category
     * 
     * @urlParam id integer required Category ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Stock category deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $category = StockCategory::findOrFail($id);
        
        // Check if category has items
        if ($category->stockItems()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete category with existing items'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Stock category deleted successfully'
        ]);
    }
}
