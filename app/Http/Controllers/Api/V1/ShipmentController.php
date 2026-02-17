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
        $shipment = Shipment::create($data);

        return response()->json([
            "ok" => true,
            "message" => "Shipment created successfully",
            "data" => $shipment,
            "token" => $token,
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            "ok" => true,
            "message" => "Shipment details",
            "data" => []
        ]);
    }

    public  function update(ShipmentFormRequest $request, $id)
    {
            $token = $this->validateUser($request->bearerToken());
            $data = $request->validated();
            $shipment = Shipment::findOrFail($id);
            $shipmentUpdated = $shipment->update($data);
        return response()->json([
            "ok" => true,
            "message" => "Shipment updated successfully",
            "data" => $shipment,
            "token" => $token
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $token = $this->validateUser($request->bearerToken());
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();
        return response()->json([
            "ok" => true,
            "message" => "Shipment deleted successfully",
            "data" => [],
            "token" => $token
        ]);
    }

    private function validateUser($token)
    {
        $tokenFromRequest = PersonalAccessToken::findToken($token);
        if (!$tokenFromRequest) {
            return response()->json([
                "ok" => false,
                "message" => "Invalid token",
                "status" => 401
            ], 401);
        }
        return $token;
    }
}
