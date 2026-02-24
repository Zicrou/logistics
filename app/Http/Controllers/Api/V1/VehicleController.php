<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Requests\V1\VehicleFormRequest;

class VehicleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }
        public function index(Request $request)
        {
            $token = $this->validateUser($request->bearerToken());
            
            return VehicleResource::collection(Vehicle::all());
        }
    
        public function store(VehicleFormRequest $request)
        {
            
            $token = $this->validateUser($request->bearerToken());
            $data = $request->validated();
            $vehicle = Vehicle::create($data);
            return response()->json([
                "message" => "Vehicle created successfully",
                "data" => $vehicle,
                "status" => 200
            ], 201);
        }
    
        public function show($id)
        {
            return response()->json([
                "message" => "Vehicle details",
                "data" => []
            ]);
        }
    
        public  function update(VehicleFormRequest $request, $id)
        {
            $token = $this->validateUser($request->bearerToken());
            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($id);
            $vehicleUpdated = $vehicle->update($data);
            return response()->json([
                "message" => "Vehicle updated successfully",
                "data" => $vehicle,
                "status" => 200
            ]);
        }
    
        public function destroy(Request $request, $id)
        {
            $token = $this->validateUser($request->bearerToken());
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();
            return response()->json([
                "message" => "Vehicle deleted successfully",
                "data" => [],
                "status" => 200
            ]);
        }

        private function validateUser($token)
    {
        $tokenFromRequest = PersonalAccessToken::findToken($token);
        if (!$tokenFromRequest) {
            return response()->json([
                "message" => "Invalid token",
                "status" => 401
            ], 401);
        }
        return $token;
    }
}
