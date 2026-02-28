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
use App\Models\CheckPoint;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
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
        $checkpoint = CheckPoint::with('shipment')->get();
        return['checkpoint' => $checkpoint];
        // return DriverReource::collection(Shipment::all());
    }

    public function store(CheckpPointFormRequest $request)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        try{
            $checkPoint = CheckPoint::create($data);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'CheckPoint not found',
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
            "message" => "Checkpoint created successfully",
            "data" => $checkPoint,
        ], 201);
    }

    public  function update(CheckpPointFormRequest $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        try{
            $checkPoint = CheckPoint::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'LocationPoint not found',
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
        $checkPoint = CheckPoint::with(relations: 'shipment')->findOrFail($id);
        if($checkPoint->update($data)){
            $driver = CheckPoint::with('shipment')->findOrFail($id);
            $checkPointUpdated = $checkPoint->load('shipment');
            return response()->json([
                "message" => "Driver updated successfully",
                "data" => $checkPointUpdated,
            ],201);
        }else{
            return response()->json(['message' => "Update error"],500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        try{
            $checkPoint = CheckPoint::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return response()->json([
                "message" => "Checkpoint deleted successfully",
            ]);
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
        
        if($checkPoint->delete()){
            return response()->json([
                "message" => "CheckPoints deleted successfully"
            ], 201);
        }else{
            return response()->json(["message" => "Delete Checkpoint error"], 500);
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
