<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;

class CartItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:add_product,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'customer_id' => 'required|integer',
            'user_id' => 'required|string',
            'reflector_color' => 'nullable|string',
            'custome_name' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // Check if product exists
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        // Check for duplicate cart item
        $existing = CartItem::where('product_id', $validated['product_id'])
            ->where('customer_id', $validated['customer_id'])
            ->where('reflector_color', $validated['reflector_color'] ?? null)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'This item already exists in this customer\'s cart.'
            ], 409);
        }

        $cartItem = CartItem::create($validated);

        return response()->json([
            'message' => 'Cart item created successfully.',
            'data' => $cartItem
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|integer|exists:add_product,id',
            'qty' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'customer_id' => 'nullable|integer',
            'user_id' => 'nullable|string',
            'reflector_color' => 'nullable|string',
            'custome_name' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found.'], 404);
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

        $cartItem->update($validated);

        return response()->json([
            'message' => 'Cart item updated successfully.',
            'data' => $cartItem
        ], 200);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:add_product,id',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'customer_id' => 'required|integer',
            'user_id' => 'required|string',
            'reflector_color' => 'nullable|string',
            'custome_name' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // Check if product exists
        $product = Product::find($validated['product_id']);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        // Check for duplicate cart item
        $existing = CartItem::where('product_id', $validated['product_id'])
            ->where('customer_id', $validated['customer_id'])
            ->where('reflector_color', $validated['reflector_color'] ?? null)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'This item already exists in this customer\'s cart.'
            ], 409);
        }

        $cartItem = CartItem::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => $cartItem
        ]);
    }

    public function getByCustomerId($customerId)
    {
        $cartItems = CartItem::with('product')->where('customer_id', $customerId)->get();

        return response()->json([
            'message' => 'Cart items fetched successfully.',
            'data' => $cartItems
        ]);
    }

    public function index(Request $request, $userId)
    {
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'No cart items found for the given user.',
            ], 404);
        }

        // Add computed final price
        $cartItems->map(function ($item) {
            $item->final_price = $item->price - ($item->price * ($item->discount ?? 0) / 100);
            return $item;
        });

        return response()->json([
            'message' => 'Cart items fetched successfully.',
            'data' => $cartItems,
        ], 200);
    }

    public function show($id)
    {
        $cartItem = CartItem::with('product')->find($id);

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        // Add computed final price
        $cartItem->final_price = $cartItem->price - ($cartItem->price * ($cartItem->discount ?? 0) / 100);

        return response()->json([
            'message' => 'Cart item fetched successfully.',
            'data' => $cartItem,
        ], 200);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json([
                'message' => 'Cart item not found.',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Cart item deleted successfully.',
        ], 200);
    }

    public function destroyByCustomer(string $customer_id)
    {
        $deleted = CartItem::where('customer_id', $customer_id)->delete();
    
        return $deleted
            ? response()->json(['message' => "Deleted {$deleted} cart item(s)."], 200)
            : response()->json(['message' => 'No cart items found.'], 404);
    }
}
