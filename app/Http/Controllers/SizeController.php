<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body_color' => 'nullable|array',
            'body_color.*' => 'nullable|string',
            'color_temp' => 'nullable|array',
            'color_temp.*' => 'nullable|string',
            'beam_angle' => 'nullable|array',
            'beam_angle.*' => 'nullable|string',
            'cut_out' => 'nullable|array',
            'cut_out.*' => 'nullable|string',
            'reflector_color' => 'nullable|array',
            'reflector_color.*' => 'nullable|string',
        ]);

        $size = Size::create($validated);

        return response()->json([
            'message' => 'Size record created successfully',
            'data' => $size,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'body_color' => 'nullable|array',
            'body_color.*' => 'nullable|string',
            'color_temp' => 'nullable|array',
            'color_temp.*' => 'nullable|string',
            'beam_angle' => 'nullable|array',
            'beam_angle.*' => 'nullable|string',
            'cut_out' => 'nullable|array',
            'cut_out.*' => 'nullable|string',
            'reflector_color' => 'nullable|array',
            'reflector_color.*' => 'nullable|string',
        ]);

        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $size->update($validated);

        return response()->json([
            'message' => 'Size record updated successfully',
            'data' => $size,
        ]);
    }

    public function show($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json([
            'message' => 'Size record retrieved successfully',
            'data' => $size
        ]);
    }

    public function index()
    {
        $sizes = Size::all();

        return response()->json([
            'message' => 'All size records retrieved successfully',
            'data' => $sizes
        ]);
    }

    public function destroy($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $size->delete();

        return response()->json([
            'message' => 'Size record deleted successfully'
        ]);
    }
}
