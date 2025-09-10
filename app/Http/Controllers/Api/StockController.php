<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Stock Management
 * 
 * APIs for managing stock items and movements
 */
class StockController extends Controller
{
    /**
     * Get Stock Items
     * 
     * Retrieve a paginated list of stock items with optional filtering
     * 
     * @queryParam category_id integer Filter by category ID
     * @queryParam type string Filter by item type
     * @queryParam search string Search in name and description
     * @queryParam low_stock boolean Filter items with low stock
     * @queryParam expired boolean Filter expired items
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {
     *     "items": [...],
     *     "pagination": {...}
     *   }
     * }
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(StockItem::class)
            ->with(['category', 'movements'])
            ->allowedFilters(['category_id', 'type', 'name', 'description'])
            ->allowedSorts(['name', 'current_quantity', 'created_at']);

        // Custom filters
        if ($request->has('low_stock') && $request->boolean('low_stock')) {
            $query->whereRaw('current_quantity <= minimum_quantity');
        }

        if ($request->has('expired') && $request->boolean('expired')) {
            $query->where('expiry_date', '<', now());
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $items->items(),
                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'per_page' => $items->perPage(),
                    'total' => $items->total()
                ]
            ]
        ]);
    }

    /**
     * Create Stock Item
     * 
     * Create a new stock item
     * 
     * @bodyParam name string required Item name
     * @bodyParam description string Item description
     * @bodyParam sku string required Unique SKU
     * @bodyParam category_id integer required Category ID
     * @bodyParam unit string required Unit of measurement
     * @bodyParam current_quantity numeric Initial quantity
     * @bodyParam minimum_quantity numeric Minimum stock level
     * @bodyParam unit_cost numeric Unit cost
     * @bodyParam expiry_date date Expiry date
     * @bodyParam supplier string Supplier name
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Stock item created successfully",
     *   "data": {...}
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:stock_items,sku',
            'category_id' => 'required|exists:stock_categories,id',
            'unit' => 'required|string|max:50',
            'current_quantity' => 'nullable|numeric|min:0',
            'minimum_quantity' => 'nullable|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $stockItem = StockItem::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Stock item created successfully',
            'data' => $stockItem->load('category')
        ], 201);
    }

    /**
     * Get Stock Item
     * 
     * Retrieve a specific stock item with its movements
     * 
     * @urlParam id integer required Stock item ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "data": {...}
     * }
     */
    public function show($id)
    {
        $stockItem = StockItem::with(['category', 'movements.user'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $stockItem
        ]);
    }

    /**
     * Update Stock Item
     * 
     * Update an existing stock item
     * 
     * @urlParam id integer required Stock item ID
     * @bodyParam name string Item name
     * @bodyParam description string Item description
     * @bodyParam sku string Unique SKU
     * @bodyParam category_id integer Category ID
     * @bodyParam unit string Unit of measurement
     * @bodyParam minimum_quantity numeric Minimum stock level
     * @bodyParam unit_cost numeric Unit cost
     * @bodyParam expiry_date date Expiry date
     * @bodyParam supplier string Supplier name
     * @bodyParam notes string Additional notes
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Stock item updated successfully",
     *   "data": {...}
     * }
     */
    public function update(Request $request, $id)
    {
        $stockItem = StockItem::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'sometimes|string|unique:stock_items,sku,' . $id,
            'category_id' => 'sometimes|exists:stock_categories,id',
            'unit' => 'sometimes|string|max:50',
            'minimum_quantity' => 'nullable|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $stockItem->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Stock item updated successfully',
            'data' => $stockItem->load('category')
        ]);
    }

    /**
     * Delete Stock Item
     * 
     * Delete a stock item
     * 
     * @urlParam id integer required Stock item ID
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Stock item deleted successfully"
     * }
     */
    public function destroy($id)
    {
        $stockItem = StockItem::findOrFail($id);
        $stockItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Stock item deleted successfully'
        ]);
    }

    /**
     * Record Stock Movement
     * 
     * Record a stock movement (in/out/adjustment)
     * 
     * @bodyParam stock_item_id integer required Stock item ID
     * @bodyParam type string required Movement type (in/out/adjustment)
     * @bodyParam quantity numeric required Movement quantity
     * @bodyParam unit_cost numeric Unit cost for this movement
     * @bodyParam reason string Reason for movement
     * @bodyParam movement_date date Movement date
     * @bodyParam notes string Additional notes
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Stock movement recorded successfully",
     *   "data": {...}
     * }
     */
    public function recordMovement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stock_item_id' => 'required|exists:stock_items,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:255',
            'movement_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $stockItem = StockItem::findOrFail($request->stock_item_id);

        // Calculate new quantity
        $currentQuantity = $stockItem->current_quantity;
        $movementQuantity = $request->quantity;

        if ($request->type === 'in') {
            $newQuantity = $currentQuantity + $movementQuantity;
        } elseif ($request->type === 'out') {
            if ($movementQuantity > $currentQuantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient stock quantity'
                ], 400);
            }
            $newQuantity = $currentQuantity - $movementQuantity;
        } else { // adjustment
            $newQuantity = $movementQuantity;
        }

        // Create movement record
        $movement = StockMovement::create([
            'stock_item_id' => $request->stock_item_id,
            'type' => $request->type,
            'quantity' => $movementQuantity,
            'unit_cost' => $request->unit_cost,
            'reason' => $request->reason,
            'user_id' => $request->user()->id,
            'movement_date' => $request->movement_date ?? now(),
            'notes' => $request->notes,
        ]);

        // Update stock quantity
        $stockItem->update(['current_quantity' => $newQuantity]);

        return response()->json([
            'status' => 'success',
            'message' => 'Stock movement recorded successfully',
            'data' => $movement->load(['stockItem', 'user'])
        ], 201);
    }
}
