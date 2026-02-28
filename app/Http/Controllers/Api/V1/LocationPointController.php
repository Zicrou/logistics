<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LocationPointFormRequest;
use App\Models\LocationPoints;
use Laravel\Sanctum\PersonalAccessToken;

use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
class LocationPointController extends Controller implements HasMiddleware
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
    $location_points = LocationPoints::with('shipment')->get();
    return['location_points' => $location_points];
        // return DriverReource::collection(Shipment::all());
    } 
    
    public function store(LocationPointFormRequest $request){
    $validate_user = $this->validateUser($request->bearerToken());
    $data = $request->validated();
    // $data['user_id'] = $validate_user['userId'];
    $location_point = LocationPoints::create($data);
    return response()->json([
        "message" => "LocationPoint created successfully",
        "data" => $location_point->load('shipment'),
    ]);
  }

  public function update(LocationPointFormRequest $request, $id)
  {
    $validate_user = $this->validateUser($request->bearerToken());
    // try{
        $location_point = LocationPoints::findOrFail($id);
    // }catch (NotFoundException $e){
    //     return ['message' => 'This location point does not exist'];
    // }
    $data = $request->validated();
    // $data['user_id'] = $validate_user['userId'];
    if($location_point->update($data)){
        $location_point = LocationPoints::with('shipment')->findOrFail($id);
        return response()->json([
            "message" => "locationPoint updated successfully",
            "data" => $location_point,
        ]);
    }else{
        return['message' => "Update error"];
    }
  }

  public function destroy(Request $request, $id){
    
    $validate_user = $this->validateUser($request->bearerToken());
    $message = ''; 
    $location_point = LocationPoints::findOrFail($id);
    if($location_point->delete()){
        $message = "LocationPoint  deleted successfully";
    }else{
        $message = "Delete locationPoint error";
    }
    return ['message' => $message];
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
        return ['token' => $token,
                'userId' => $tokenFromRequest->tokenable_id];
    }
}
