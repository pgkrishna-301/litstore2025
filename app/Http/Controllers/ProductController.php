<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
public function productStored(Request $request)
{
    // ✅ Validate request
    $validatedData = $request->validate([
        'banner_image' => 'nullable|file|image|max:2048',
        'add_image' => 'nullable',
        'add_image.*' => 'nullable|file|image|max:2048',
        'product_name' => 'nullable|string',
        'product_category' => 'nullable|string',
        'mrp' => 'nullable|numeric',
        'hns_code' => 'nullable|string',
        'dimensions' => 'nullable|string',
        'driver_output' => 'nullable|string',
        'ip_rating' => 'nullable|string',
        'body_color' => 'nullable|array',
        'body_color.*' => 'nullable|string',
        'color_temp' => 'nullable|array',
        'color_temp.*' => 'nullable|string',
        'beam_angle' => 'nullable|array',
        'beam_angle.*' => 'nullable|string',
        'cut_out' => 'nullable|array',
        'cut_out.*' => 'nullable|string',
        'description' => 'nullable|string',
        'product_details' => 'nullable|array',
        'product_details.*.reflector_color' => 'nullable|string',
        'product_details.*.net_quantity' => 'nullable|integer',
    ]);

    // ✅ Store full URL for banner image
    if ($request->hasFile('banner_image')) {
        $path = $request->file('banner_image')->store('uploads/images', 'public');
        $validatedData['banner_image'] = url('storage/' . $path); // Store full URL
    }

    // ✅ Handle multiple additional images
    $imagePaths = [];

    // 1️⃣ If files are uploaded
    if ($request->hasFile('add_image')) {
        foreach ($request->file('add_image') as $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('uploads/images', 'public');
                $imagePaths[] = url('storage/' . $path); // Store full URL
            }
        }
    }

    // 2️⃣ If input is URL or string path
    if ($request->filled('add_image') && !$request->hasFile('add_image')) {
        $addImageInput = $request->input('add_image');
        if (is_string($addImageInput)) {
            $imagePaths[] = $addImageInput;
        } elseif (is_array($addImageInput)) {
            foreach ($addImageInput as $img) {
                if (is_string($img)) {
                    $imagePaths[] = $img;
                }
            }
        }
    }

    // ✅ Encode as JSON with full URLs
    $validatedData['add_image'] = !empty($imagePaths) ? json_encode($imagePaths) : null;

    // ✅ Store product in DB
    $product = Product::create($validatedData);

    return response()->json([
        'success' => true,
        'data' => $product,
    ], 201);
}






    
public function updateProduct(Request $request, $id)
{
    Log::info('Request Data:', $request->all());

    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found.',
        ], 404);
    }

    Log::info('Product Found:', $product->toArray());

    // Handle banner image
    if ($request->hasFile('banner_image')) {
        $request->validate([
            'banner_image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('banner_image')->store('uploads/images', 'public');
        $product->banner_image = url('storage/' . $path); // ✅ dynamic full URL

        Log::info('Banner Image Uploaded:', ['url' => $product->banner_image]);
    }

    // Handle add_image uploads (as files)
    if ($request->hasFile('add_image')) {
        $request->validate([
            'add_image' => 'array',
            'add_image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $newImages = [];
        foreach ($request->file('add_image') as $file) {
            $storedPath = $file->store('uploads/images', 'public');
            $newImages[] = url('storage/' . $storedPath);
        }

        $product->add_image = json_encode($newImages, JSON_UNESCAPED_SLASHES);
        Log::info('Additional Images Stored:', ['add_image' => $product->add_image]);
    }

    // Handle add_image as string or array of strings
    if ($request->filled('add_image') && !$request->hasFile('add_image')) {
        $addImageInput = $request->input('add_image');
        $imageArray = [];

        if (is_string($addImageInput)) {
            $imageArray = [filter_var($addImageInput, FILTER_VALIDATE_URL) ? $addImageInput : url('storage/' . ltrim($addImageInput, '/'))];
        } elseif (is_array($addImageInput)) {
            foreach ($addImageInput as $img) {
                $imageArray[] = filter_var($img, FILTER_VALIDATE_URL) ? $img : url('storage/' . ltrim($img, '/'));
            }
        }

        $product->add_image = json_encode($imageArray, JSON_UNESCAPED_SLASHES);
    }

    // Handle array fields
    $arrayFields = ['body_color', 'color_temp', 'beam_angle', 'cut_out'];
    foreach ($arrayFields as $field) {
        if ($request->filled($field)) {
            $product->$field = $request->input($field);
        }
    }

    // Handle product_details array
    if ($request->filled('product_details')) {
        $product->product_details = $request->input('product_details');
    }

    // Update remaining fields
    $product->fill($request->except([
        'banner_image',
        'add_image',
        'body_color',
        'color_temp',
        'beam_angle',
        'cut_out',
        'product_details',
    ]));

    $product->save();

    Log::info('Product Updated:', $product->toArray());

    return response()->json([
        'success' => true,
        'data' => $product,
    ], 200);
}



    public function getAllProducts()
    {
        $products = Product::all();

        $products = $products->map(function ($product) {
            // Handle banner image URL
            if (!empty($product->banner_image)) {
                if (filter_var($product->banner_image, FILTER_VALIDATE_URL)) {
                    $product->banner_image_url = $product->banner_image;
                } else {
                    $product->banner_image_url = url('storage/' . $product->banner_image);
                }
            } else {
                $product->banner_image_url = null;
            }

            // Handle additional images
            $addImages = [];
            if (!empty($product->add_image)) {
                $decodedImages = is_array($product->add_image) ? $product->add_image : json_decode($product->add_image, true);
                if (is_array($decodedImages)) {
                    foreach ($decodedImages as $img) {
                        if (filter_var($img, FILTER_VALIDATE_URL)) {
                            $addImages[] = $img;
                        } else {
                            $addImages[] = url('storage/' . $img);
                        }
                    }
                }
            }
            $product->add_image_url = $addImages;

            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products,
        ], 200);
    }

    public function getProductById($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // Handle banner image URL
        if (!empty($product->banner_image)) {
            if (filter_var($product->banner_image, FILTER_VALIDATE_URL)) {
                $product->banner_image_url = $product->banner_image;
            } else {
                $product->banner_image_url = url('storage/' . $product->banner_image);
            }
        } else {
            $product->banner_image_url = null;
        }

        // Handle additional images
        $addImages = [];
        if (!empty($product->add_image)) {
            $decodedImages = is_array($product->add_image) ? $product->add_image : json_decode($product->add_image, true);
            if (is_array($decodedImages)) {
                foreach ($decodedImages as $img) {
                    if (filter_var($img, FILTER_VALIDATE_URL)) {
                        $addImages[] = $img;
                    } else {
                        $addImages[] = url('storage/' . $img);
                    }
                }
            }
        }
        $product->add_image_url = $addImages;

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 200);
    }

    public function deleteProduct($id)
    {
        // Log the incoming delete request
        Log::info('Delete Request for Product ID:', ['id' => $id]);

        // Find the product by ID
        $product = Product::find($id);

        // If the product is not found, return a 404 response
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // Delete associated files if they exist
        if ($product->banner_image && Storage::disk('public')->exists($product->banner_image)) {
            Storage::disk('public')->delete($product->banner_image);
            Log::info('Banner image deleted:', ['path' => $product->banner_image]);
        } elseif ($product->banner_image && filter_var($product->banner_image, FILTER_VALIDATE_URL)) {
            // Extract path from full URL for deletion
            $path = str_replace(url('storage/'), '', $product->banner_image);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info('Banner image deleted:', ['path' => $path]);
            }
        }

        if ($product->add_image) {
            $addImages = is_array($product->add_image) ? $product->add_image : json_decode($product->add_image, true);
            if (is_array($addImages)) {
                foreach ($addImages as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                        Log::info('Additional image deleted:', ['path' => $imagePath]);
                    } elseif (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                        // Extract path from full URL for deletion
                        $path = str_replace(url('storage/'), '', $imagePath);
                        if (Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->delete($path);
                            Log::info('Additional image deleted:', ['path' => $path]);
                        }
                    }
                }
            }
        }

        // Delete the product
        $product->delete();

        // Log the deletion
        Log::info('Product deleted successfully:', ['id' => $id]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ], 200);
    }
}
