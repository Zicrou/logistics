<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransportFormRequest;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Models\Shipment;
use App\Models\Transport;

class TransportController extends Controller implements HasMiddleware
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
        $transport = Transport::with('shipment')->get();
        return['transport' => $transport];
    }

    public function store(TransportFormRequest $request)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        
        $transport = Transport::create($data);

        return response()->json([
            "message" => "Transport created successfully",
            "data" => $transport,
        ], 201);
    }

    public  function update(TransportFormRequest $request, $id)
    {
            $validate_user = $this->validateUser($request->bearerToken());
            $transport = Transport::findOrFail($id);
            $data = $request->validated();
            //    return['transport'=> $transport]; 
            if($transport->update($data)){
                $transport = Transport::with('shipment')->findOrFail($id);
                return response()->json([
                    "message" => "Transport updated successfully",
                    "data" => $transport,
                ]);
            }else{
                return['message' => "Update error"];
            }
    }

    public function destroy(Request $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $transport = Transport::findOrFail($id);
        $transport->delete();
        return response()->json([
            "message" => "Transport deleted successfully",
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
