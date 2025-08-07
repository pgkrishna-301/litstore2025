<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
 public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:add_product,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'reflector_color' => 'nullable|string',
        ]);

        // Check if product exists
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        // Check for duplicate purchase item
        $existing = Purchase::where('product_id', $validated['product_id'])
            ->where('reflector_color', $validated['reflector_color'] ?? null)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'This item already exists in purchase.'
            ], 409);
        }

        $purchaseItem = Purchase::create($validated);

        return response()->json([
            'message' => 'Purchase item created successfully.',
            'data' => $purchaseItem
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|integer|exists:add_product,id',
            'qty' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'reflector_color' => 'nullable|string',
        ]);

        $purchaseItem = Purchase::find($id);

        if (!$purchaseItem) {
            return response()->json(['message' => 'Purchase item not found.'], 404);
        }

        // Check if product exists if product_id is being updated
        if (isset($validated['product_id'])) {
            $product = Product::find($validated['product_id']);
            if (!$product) {
                return response()->json([
                    'message' => 'Product not found.'
                ], 404);
            }
        }

        $purchaseItem->update($validated);

        return response()->json([
            'message' => 'Purchase item updated successfully.',
            'data' => $purchaseItem
        ], 200);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:add_product,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'reflector_color' => 'nullable|string',
        ]);

        // Check if product exists
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        // Check for duplicate purchase item
        $existing = Purchase::where('product_id', $validated['product_id'])
            ->where('reflector_color', $validated['reflector_color'] ?? null)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'This item already exists in purchase.'
            ], 409);
        }

        $purchaseItem = Purchase::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item added to purchase successfully',
            'data' => $purchaseItem
        ]);
    }

    public function getByCustomerId($customerId)
    {
        $purchaseItems = Purchase::with('product')->get();

        return response()->json([
            'message' => 'Purchase items fetched successfully.',
            'data' => $purchaseItems
        ]);
    }

    public function index(Request $request, $userId)
    {
        $purchaseItems = Purchase::with('product')->get();

        if ($purchaseItems->isEmpty()) {
            return response()->json([
                'message' => 'No purchase items found.',
            ], 404);
        }

        // Add computed final price
        $purchaseItems->map(function ($item) {
            $item->final_price = $item->price - ($item->price * ($item->discount ?? 0) / 100);
            return $item;
        });

        return response()->json([
            'message' => 'Purchase items fetched successfully.',
            'data' => $purchaseItems,
        ], 200);
    }

    public function show($id)
    {
        $purchaseItem = Purchase::with('product')->find($id);

        if (!$purchaseItem) {
            return response()->json([
                'message' => 'Purchase item not found.',
            ], 404);
        }

        // Add computed final price
        $purchaseItem->final_price = $purchaseItem->price - ($purchaseItem->price * ($purchaseItem->discount ?? 0) / 100);

        return response()->json([
            'message' => 'Purchase item fetched successfully.',
            'data' => $purchaseItem,
        ], 200);
    }

    public function destroy($id)
    {
        $purchaseItem = Purchase::find($id);

        if (!$purchaseItem) {
            return response()->json([
                'message' => 'Purchase item not found.',
            ], 404);
        }

        $purchaseItem->delete();

        return response()->json([
            'message' => 'Purchase item deleted successfully.',
        ], 200);
    }

    public function getAll()
{
    $purchaseItems = Purchase::with('product')->get();

    if ($purchaseItems->isEmpty()) {
        return response()->json([
            'message' => 'No purchase items found.',
            'data' => []
        ], 200);
    }

    // Add computed final price for each item
    $purchaseItems->map(function ($item) {
        $item->final_price = $item->price - ($item->price * ($item->discount ?? 0) / 100);
        return $item;
    });

    return response()->json([
        'message' => 'All purchase items fetched successfully.',
        'data' => $purchaseItems,
    ], 200);
}


    public function destroyByCustomer(string $customer_id)
    {
        $deleted = Purchase::delete();
    
        return $deleted
            ? response()->json(['message' => "Deleted {$deleted} purchase item(s)."], 200)
            : response()->json(['message' => 'No purchase items found.'], 404);
    }
}
