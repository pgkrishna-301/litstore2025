<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Architect;

class OrderDetailController extends Controller
{
public function store(Request $request)
{
    // Validate the request (all 'required' removed)
    $validatedData = $request->validate([
        'order_id' => 'nullable|string|max:255',
        'user_id' => 'nullable|string',
        'products' => 'nullable|array',
        'products.*.product_id' => 'nullable|integer|exists:add_product,id',
        'products.*.reflector_color' => 'nullable|string',
        'products.*.location' => 'nullable|array',
        'products.*.location.*' => 'nullable|string',
        'products.*.qty' => 'nullable|integer',
        'products.*.price' => 'nullable|numeric',
        'products.*.custome_name' => 'nullable|string|max:255', // ✅ Changed field name
        'total_price' => 'nullable|numeric',
        'status' => 'nullable|integer',
        'cash' => 'nullable|string|max:255',
        'credit' => 'nullable|string|max:255',
        'received' => 'nullable|string|max:255',
        'pending' => 'nullable|string|max:255',
        'discount_price' => 'nullable|string|max:255',
        'discount_status' => 'nullable|integer|max:255',
        'discount_stage' => 'nullable|integer|max:255',
        'customer_id' => 'nullable|integer|exists:architects,id',
        'quotation_id' => 'nullable|string|max:255',
        'quotation_status' => 'nullable|integer|max:255',
        'delivery_date' => 'nullable|date',
    ]);

    // Calculate total price if not provided
    if (!isset($validatedData['total_price']) && isset($validatedData['products'])) {
        $totalPrice = 0;
        foreach ($validatedData['products'] as $product) {
            $totalPrice += ($product['qty'] ?? 0) * ($product['price'] ?? 0);
        }
        $validatedData['total_price'] = $totalPrice;
    }

    // Process products array
    $productsArray = [];
    if (!empty($validatedData['products'])) {
        foreach ($validatedData['products'] as $product) {
            $productsArray[] = [
                'product_id' => $product['product_id'] ?? null,
                'reflector_color' => $product['reflector_color'] ?? null,
                'location' => $product['location'] ?? [],
                'qty' => $product['qty'] ?? 0,
                'price' => $product['price'] ?? 0,
                'custome_name' => $product['custome_name'] ?? null, // ✅ Changed field name
            ];
        }
    }

    // Store order
    $order = OrderDetail::create([
        'order_id' => $validatedData['order_id'] ?? null,
        'user_id' => $validatedData['user_id'] ?? null,
        'products' => $productsArray,
        'total_price' => $validatedData['total_price'] ?? 0,
        'status' => $validatedData['status'] ?? null,
        'cash' => $validatedData['cash'] ?? null,
        'credit' => $validatedData['credit'] ?? null,
        'received' => $validatedData['received'] ?? null,
        'pending' => $validatedData['pending'] ?? null,
        'customer_id' => $validatedData['customer_id'] ?? null,
        'discount_status' => $validatedData['discount_status'] ?? 0,
        'discount_stage' => $validatedData['discount_stage'] ?? 0,
        'discount_price' => $validatedData['discount_price'] ?? null,
        'quotation_id' => $validatedData['quotation_id'] ?? null,
        'quotation_status' => $validatedData['quotation_status'] ?? 0,
        'delivery_date' => $validatedData['delivery_date'] ?? null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Order stored successfully!',
        'data' => $order->load('customer')
    ], 201);
}



public function update(Request $request, $id)
{
    $order = OrderDetail::find($id);

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Order not found',
        ], 404);
    }

    // Validate request (all nullable)
    $validatedData = $request->validate([
        'order_id' => 'nullable|string|max:255',
        'user_id' => 'nullable|string',
        'products' => 'nullable|array',
        'products.*.product_id' => 'nullable|integer|exists:add_product,id',
        'products.*.reflector_color' => 'nullable|string',
        'products.*.location' => 'nullable|array',
        'products.*.location.*' => 'nullable|string',
        'products.*.qty' => 'nullable|integer',
        'products.*.price' => 'nullable|numeric',
        'total_price' => 'nullable|numeric',
        'status' => 'nullable|integer',
        'cash' => 'nullable|string|max:255',
        'credit' => 'nullable|string|max:255',
        'received' => 'nullable|string|max:255',
        'discount_price' => 'nullable|string|max:255',
        'discount_status' => 'nullable|integer|max:255',
        'quotation_status' => 'nullable|integer|max:255',
        'discount_stage' => 'nullable|integer|max:255',
        'pending' => 'nullable|string|max:255',
        'customer_id' => 'nullable|integer|exists:architects,id',
        'quotation_id' => 'nullable|string|max:255',
        'delivery_date' => 'nullable|date',
    ]);

    // ✅ Process products
    if (isset($validatedData['products'])) {
        $productsArray = [];
        foreach ($validatedData['products'] as $product) {
            $productsArray[] = [
                'product_id' => $product['product_id'] ?? null,
                'reflector_color' => $product['reflector_color'] ?? null,
                'location' => $product['location'] ?? [],
                'qty' => $product['qty'] ?? 0,
                'price' => $product['price'] ?? 0
            ];
        }
        $order->products = $productsArray;

        // Calculate total price if not given
        if (!isset($validatedData['total_price'])) {
            $totalPrice = 0;
            foreach ($validatedData['products'] as $product) {
                $totalPrice += ($product['qty'] ?? 0) * ($product['price'] ?? 0);
            }
            $validatedData['total_price'] = $totalPrice;
        }
    }

    // ✅ Update other fields
    foreach ($validatedData as $key => $value) {
        if ($key !== 'products') {
            $order->$key = $value;
        }
    }

    $order->save();

    return response()->json([
        'success' => true,
        'message' => 'Order updated successfully!',
        'data' => $order->load('customer')
    ], 200);
}


    public function updateOrderByCustomerId(Request $request, $customer_id)
    {
        $order = OrderDetail::where('customer_id', $customer_id)->with('customer')->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found for the given customer ID',
            ], 404);
        }

        $validatedData = $request->validate([
            'order_id' => 'nullable|string|max:255',
            'user_id' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|integer|exists:add_product,id',
            'products.*.reflector_color' => 'nullable|string',
            'products.*.location' => 'nullable|array',
            'products.*.location.*' => 'nullable|string',
            'products.*.qty' => 'nullable|integer',
            'products.*.price' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'status' => 'nullable|integer',
            'cash' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'received' => 'nullable|string|max:255',
            'pending' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
        ]);

        if (isset($validatedData['products'])) {
            $productsArray = [];
            foreach ($validatedData['products'] as $product) {
                $productsArray[] = [
                    'product_id' => $product['product_id'] ?? null,
                    'reflector_color' => $product['reflector_color'] ?? null,
                    'location' => $product['location'] ?? [],
                    'qty' => $product['qty'] ?? 0,
                    'price' => $product['price'] ?? 0
                ];
            }
            $order->products = $productsArray;
        }

        if (isset($validatedData['products']) && !isset($validatedData['total_price'])) {
            $totalPrice = 0;
            foreach ($validatedData['products'] as $product) {
                $totalPrice += ($product['qty'] ?? 0) * ($product['price'] ?? 0);
            }
            $validatedData['total_price'] = $totalPrice;
        }

        foreach ($validatedData as $key => $value) {
            if ($key !== 'products') {
                $order->$key = $value;
            }
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!',
            'data' => $order->load('customer')
        ], 200);
    }
    public function getById($id)
    {
        $order = OrderDetail::with('customer')->find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $order], 200);
    }

    public function getAllOrders()
{
    $orders = OrderDetail::with('customer')->get();

    if ($orders->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No orders found',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $orders,
    ], 200);
}


    /**
     * ✅ 3. Get Orders by User ID (Route: GET order-details/user/{userId})
     */
    public function getByUserId($userId)
    {
        $orders = OrderDetail::where('user_id', $userId)->with('customer')->get();

        if ($orders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No orders found for this user'], 404);
        }

        return response()->json(['success' => true, 'data' => $orders], 200);
    }

    /**
     * ✅ 4. Update Order by Order ID (Route: PUT order/update/{order_id})
     */
    public function updateorderId(Request $request, $order_id)
    {
        $order = OrderDetail::where('order_id', $order_id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $validatedData = $this->validateOrder($request);

        if (isset($validatedData['products'])) {
            $order->products = $this->processProducts($validatedData['products']);

            if (!isset($validatedData['total_price'])) {
                $validatedData['total_price'] = $this->calculateTotal($validatedData['products']);
            }
        }

        foreach ($validatedData as $key => $value) {
            if ($key !== 'products') {
                $order->$key = $value;
            }
        }

        $order->save();

        return response()->json(['success' => true, 'message' => 'Order updated successfully!', 'data' => $order->load('customer')], 200);
    }

    /**
     * ✅ 5. Update Order by Customer ID (Route: POST update-order/{customer_id})
     */
   

    /**
     * ✅ 6. Delete Order by Order ID (Route: DELETE orders/{order_id})
     */
    public function destroy($order_id)
    {
        $order = OrderDetail::where('order_id', $order_id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully'], 200);
    }

    /**
     * ✅ 7. Get Orders by Customer ID (Route: GET orders/customer/{customer_id})
     */
    public function getOrdersByCustomerId($customer_id)
    {
        $orders = OrderDetail::where('customer_id', $customer_id)->with('customer')->get();

        if ($orders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No orders found for this customer'], 404);
        }

        return response()->json(['success' => true, 'data' => $orders], 200);
    }
    public function getOrderByOrderId($order_id)
    {
        $order = OrderDetail::where('order_id', $order_id)->with('customer')->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found for the given Order ID',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ], 200);
    }

    // ----------------------
    // ✅ Helper functions
    // ----------------------

    private function validateOrder($request)
    {
        return $request->validate([
            'order_id' => 'nullable|string|max:255',
            'user_id' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|integer|exists:add_product,id',
            'products.*.reflector_color' => 'nullable|string',
            'products.*.location' => 'nullable|array',
            'products.*.location.*' => 'nullable|string',
            'products.*.qty' => 'nullable|integer',
            'products.*.price' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'status' => 'nullable|integer',
            'cash' => 'nullable|string|max:255',
            'credit' => 'nullable|string|max:255',
            'received' => 'nullable|string|max:255',
            'pending' => 'nullable|string|max:255',
            'discount_price' => 'nullable|string|max:255',
            'discount_status' => 'nullable|integer|max:255',
            'discount_stage' => 'nullable|integer|max:255',
            'quotation_status' => 'nullable|integer|max:255',
            'customer_id' => 'nullable|integer|exists:architects,id',
            'delivery_date' => 'nullable|date',
        ]);
    }

    private function processProducts($products)
    {
        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = [
                'product_id' => $product['product_id'] ?? null,
                'reflector_color' => $product['reflector_color'] ?? null,
                'location' => $product['location'] ?? [],
                'qty' => $product['qty'] ?? 0,
                'price' => $product['price'] ?? 0
            ];
        }
        return $productsArray;
    }

    private function calculateTotal($products)
    {
        $total = 0;
        foreach ($products as $product) {
            $total += ($product['qty'] ?? 0) * ($product['price'] ?? 0);
        }
        return $total;
    }


    

    // Same changes apply to updateorderId and update methods (just replace 'product_details' → 'products' and remove 'required')
}
