<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CheckpPointFormRequest;
use App\Http\Requests\V1\CheckpPointRequest;
use Illuminate\Http\Request;
use App\Models\Driver;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Resources\ShipmentResource;
use App\Models\CheckPoints;
use Illuminate\Validation\Rule;
class CheckPointController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }
    public function index(Request $request)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $checkpoint = CheckPoints::with('shipment')->get();
        return['checkpoint' => $checkpoint];
        // return DriverReource::collection(Shipment::all());
    }

    public function store(CheckpPointFormRequest $request)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        $checkPoint = CheckPoints::create($data);

        return response()->json([
            "message" => "Checkpoint created successfully",
            "data" => $checkPoint,
        ], 201);
    }

    public  function update(CheckpPointFormRequest $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $checkPoint = CheckPoints::findOrFail($id);
        $data = $request->validated();
            
        $checkPointUpdated = $checkPoint->update($data);
        $checkPoint = CheckPoints::with(relations: 'shipment')->findOrFail($id);
        return response()->json([
            "message" => "Driver updated successfully",
            "data" => $checkPoint,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $checkPoints = CheckPoints::findOrFail($id);
        $checkPoints->delete();
        return response()->json([
            "message" => "Checkpoint deleted successfully",

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
