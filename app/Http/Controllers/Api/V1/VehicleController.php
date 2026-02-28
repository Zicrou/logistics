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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
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
            try{
                $vehicle = Vehicle::create($data);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Vehicle not found',
                ], 404);
            }catch (QueryException $e) {
                return response()->json([
                    'message' => 'Database error',
                    // 'error' => $e->getMessage(), // enable only in debug
                ], 500);

            } catch (Throwable $e) {
                return response()->json([
                    'message' => 'Unexpected server error',
                ], 500);
            }
            return response()->json([
                "message" => "Document created successfully",
                "data" => $vehicle,
            ], 201);
        }
    
        public  function update(VehicleFormRequest $request, $id)
        {
            $token = $this->validateUser($request->bearerToken());
            $data = $request->validated();
            try{
                $vehicle = Vehicle::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Vehicle not found',
                ], 404);
            }catch (QueryException $e) {
                return response()->json([
                    'message' => 'Database error',
                    // 'error' => $e->getMessage(), // enable only in debug
                ], 500);

            } catch (Throwable $e) {
                return response()->json([
                    'message' => 'Unexpected server error',
                ], 500);
            }
            if($vehicle->update($data)){
                $vehicle = Vehicle::with('shipment')->findOrFail($id);
                return response()->json([
                    "message" => "Vehicle updated successfully",
                    "data" => $vehicle,
                ],201);
            }else{
                return response()->json(['message' => "Update error"], 500);
            }
        }
    
        public function destroy(Request $request, $id)
        {
            $token = $this->validateUser($request->bearerToken());            
            try{
                $vehicle = Vehicle::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Vehicle not found',
                ], 404);
            }catch (QueryException $e) {
                return response()->json([
                    'message' => 'Database error',
                    // 'error' => $e->getMessage(), // enable only in debug
                ], 500);

            } catch (Throwable $e) {
                return response()->json([
                    'message' => 'Unexpected server error',
                ], 500);
            }
            if($vehicle->delete()){
                return response()->json([
                    "message" => "Vehicle deleted successfully"
                ], 201);
            }else{
                return response()->json(["message" => "Delete Document error"], 404);
            }
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
