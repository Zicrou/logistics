<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverFormRequest;
use App\Http\Requests\DriverUpdateFormRequest;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Driver;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Resources\ShipmentResource;
use Illuminate\Validation\Rule;

class DriverController extends Controller implements HasMiddleware
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
        $driver = Driver::with('vehicle')->get();
        return['driver' => $driver];
        // return DriverReource::collection(Shipment::all());
    }

    public function store(DriverFormRequest $request)
    {
        $token = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        $driver = Driver::create($data);

        return response()->json([
            "message" => "Driver created successfully",
            "data" => $driver,
        ], 201);
    }

    public  function update(DriverUpdateFormRequest $request, $id)
    {
            $token = $this->validateUser($request->bearerToken());
            $driver = Driver::findOrFail($id);
            $data = $request->validated();
                
            $driverUpdated = $driver->update($data);
            $driver = Driver::findOrFail($id)->with('vehicles');
            return response()->json([
            "message" => "Driver updated successfully",
            "data" => $driver,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $token = $this->validateUser($request->bearerToken());
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return response()->json([
            "message" => "Driver deleted successfully",

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
