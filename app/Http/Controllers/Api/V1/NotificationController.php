<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\NotificationFormRequest;
use Illuminate\Http\Request;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Resources\ShipmentResource;
use App\Models\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
class NotificationController extends Controller implements HasMiddleware
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
        $notification = Notification::with('shipment')->get();
        return['notification$notification' => $notification];
        // return DriverReource::collection(Shipment::all());
    }

    public function store(NotificationFormRequest $request)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        try{
            $notification = Notification::create($data);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Notification not found',
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
            "message" => "Notification created successfully",
            "data" => $notification,
        ], 201);
    }

    public  function update(NotificationFormRequest $request, $id)
    {
            $validate_user = $this->validateUser($request->bearerToken());
            
            $data = $request->validated();
                
            try{
                $notification = Notification::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Notification not found',
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
            if($notification->update($data)){
                $notification = Notification::with('shipment')->findOrFail($id);
                return response()->json([
                    "message" => "Notification updated successfully",
                    "data" => $notification,
                ],201);
            }else{
                return response()->json(['message' => "Update error"],500);
            }
    }

    public function destroy(Request $request, $id)
    {
        $token = $this->validateUser($request->bearerToken());
        try{
            $notification = Notification::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    "message" => "Notification not found",

                ],404);
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
            if($notification->delete()){
                return response()->json([
                    "message" => "Notification deleted successfully"
                ], 500);
            }else{
                return response()->json(["message" => "Delete Notification error"], 500);
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
