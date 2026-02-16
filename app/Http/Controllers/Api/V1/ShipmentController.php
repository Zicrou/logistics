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


class ShipmentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }
    public function index()
    {
        return response()->json([
            "ok" => true,
            "message" => "List of shipments",
            "data" => []
        ]);
    }

    public function store(ShipmentFormRequest $request)
    {
        $tokenString = $request->bearerToken(); // Just the token string, no "Bearer"
        $tokenFromRequest = PersonalAccessToken::findToken($tokenString);
        $userExist = User::find($tokenFromRequest->tokenable_id);
            if (!$userExist) {
                return response()->json([
                    "ok" => false,
                    "message" => "Invalid token",
                    "status" => 401
                ], 401);
            }
        $data = $request->validated();
        $shipment = Shipment::create($data);

        return response()->json([
            "ok" => true,
            "message" => "Shipment created successfully",
            "data" => $shipment,
            'user' => $userExist,
            "token" => $tokenFromRequest,
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

    public  function update(Request $request, $id)
    {
        return response()->json([
            "ok" => true,
            "message" => "Shipment updated successfully",
            "data" => []
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            "ok" => true,
            "message" => "Shipment deleted successfully",
            "data" => []
        ]);
    }
}
