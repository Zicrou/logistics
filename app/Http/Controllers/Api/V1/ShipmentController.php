<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\ShipmentFormRequest;
use App\Models\Shipment;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Resources\ShipmentResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
class ShipmentController extends Controller implements HasMiddleware
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
        return ShipmentResource::collection(Shipment::all());
    }

    public function store(ShipmentFormRequest $request)
    {
        $token = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        try{
            $shipment = Shipment::create($data);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Shipment not found',
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
            "message" => "Shipment created successfully",
            "data" => $shipment,
            "token" => $token,
        ], 201);
    }


    public  function update(ShipmentFormRequest $request, $id)
    {
            $token = $this->validateUser($request->bearerToken());
            $data = $request->validated();
            try{
                $shipment = Shipment::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Shipment not found',
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

            if($shipment->update($data)){
                $shipment = Shipment::findOrFail($id);
                return response()->json([
                    "message" => "Shipment updated successfully",
                    "data" => $shipment->load('shipment'),
                ],201);
            }else{
                return response()->json(['message' => "Update error"],500);
            }
    }

    public function destroy(Request $request, $id)
    {
        $token = $this->validateUser($request->bearerToken());
        
        try{
            $shipment = Shipment::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Shipment not found',
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
        if($shipment->delete()){
            return response()->json([
                "message" => "Shipment deleted successfully"
            ], 201);
        }else{
            return response()->json(["message" => "Delete Shipment error"], 404);
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
