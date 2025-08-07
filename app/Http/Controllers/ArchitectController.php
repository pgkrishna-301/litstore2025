<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Architect;
use App\Models\Profession;
use Illuminate\Support\Facades\Validator;

class ArchitectController extends Controller {
    
    // Store Architect Data
    public function store(Request $request) {
        // Validate Request Data
        $validator = Validator::make($request->all(), [
            'select_architect' => 'required|integer|exists:add_profession,id',
            'name' => 'required|string|max:255',
            'firm_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|',
            'ph_no' => 'required|string|',
            'shipping_address' => 'required|string',
            'status' => 'nullable|integer',
           
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Store Data
        $architect = Architect::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Architect details saved successfully',
            'data' => $architect->load('profession'),
        ], 201);
    }

    // Get All Architects
    public function getAllArchitects() {
        $architects = Architect::with('profession')->get();

        return response()->json([
            'status' => true,
            'message' => 'All architect details fetched successfully',
            'data' => $architects,
        ], 200);
    }

    // Get Single Architect by ID
    public function getArchitectById($id) {
        $architect = Architect::with('profession')->find($id);

        if (!$architect) {
            return response()->json([
                'status' => false,
                'message' => 'Architect not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Architect details fetched successfully',
            'data' => $architect,
        ], 200);
    }

    // Update Architect Data
    public function update(Request $request, $ph_no) {
        // Find architect by phone number
        $architect = Architect::where('ph_no', $ph_no)->first();
    
        if (!$architect) {
            return response()->json([
                'status' => false,
                'message' => 'Architect not found',
            ], 404);
        }
    
        // Validate Request Data
        $validator = Validator::make($request->all(), [
            'select_architect' => 'nullable|integer|exists:add_profession,id',
            'name' => 'string|max:255',
            'firm_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email',
            'ph_no' => 'string|max:15', 
            'shipping_address' => 'string',
            'status' => 'nullable|integer|',
            
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Update Data
        $architect->update($request->all());
    
        return response()->json([
            'status' => true,
            'message' => 'Architect details updated successfully',
            'data' => $architect->load('profession'),
        ], 200);
    }
    
// Update Architect Data
public function updateAll(Request $request) {
    // Validate Request Data
    $validator = Validator::make($request->all(), [
        'select_architect' => 'nullable|integer|exists:add_profession,id',
        'name' => 'nullable|string|max:255',
        'firm_name' => 'nullable|string|max:255',
        'email' => 'nullable|string|email',
        'ph_no' => 'nullable|string',
        'shipping_address' => 'nullable|string',
        'status' => 'nullable|integer',
       
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Update all records
    $updatedRows = Architect::query()->update($request->only([
        'select_architect',
        'name',
        'firm_name',
        'email',
        'ph_no',
        'shipping_address',
        'status',
        
    ]));

    return response()->json([
        'status' => true,
        'message' => 'All architect records updated successfully',
        'updated_rows' => $updatedRows
    ], 200);
}




    // Delete Architect
    public function destroy($id) {
        $architect = Architect::find($id);

        if (!$architect) {
            return response()->json([
                'status' => false,
                'message' => 'Architect not found',
            ], 404);
        }

        $architect->delete();

        return response()->json([
            'status' => true,
            'message' => 'Architect deleted successfully',
        ], 200);
    }

    public function getByCreator($creator)
{
    $architects = Architect::where('creator', $creator)->with('profession')->get();

    if ($architects->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No architects found for this creator.',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $architects,
    ]);
}

    // Get architects by profession
    public function getByProfession($professionId)
    {
        $profession = Profession::find($professionId);
        
        if (!$profession) {
            return response()->json([
                'status' => false,
                'message' => 'Profession not found',
            ], 404);
        }

        $architects = $profession->architects;

        return response()->json([
            'status' => true,
            'message' => 'Architects for profession fetched successfully',
            'profession' => $profession,
            'architects' => $architects,
        ], 200);
    }

    // Test method to verify foreign key relationship
    public function testRelationship()
    {
        try {
            // Get an architect with its profession
            $architect = Architect::with('profession')->first();
            
            if (!$architect) {
                return response()->json([
                    'status' => false,
                    'message' => 'No architects found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Foreign key relationship working correctly',
                'architect' => $architect,
                'profession' => $architect->profession
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error testing relationship',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
