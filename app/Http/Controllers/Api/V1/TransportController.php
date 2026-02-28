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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
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
        
        try{
            $transport = Transport::create($data);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Transport not found',
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
            "message" => "Transport created successfully",
            "data" => $transport,
        ], 201);
    }

    public  function update(TransportFormRequest $request, $id)
    {
            $validate_user = $this->validateUser($request->bearerToken());
            try{
                $transport = Transport::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Transport not found',
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
            $data = $request->validated();
            //    return['transport'=> $transport]; 
            if($transport->update($data)){
                $transport = Transport::with('shipment')->findOrFail($id);
                return response()->json([
                    "message" => "Transport updated successfully",
                    "data" => $transport,
                ], 500);
            }else{
                return response()->json(['message' => "Update error"], 500);
            }
    }

    public function destroy(Request $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        try{
            $transport = Transport::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Transport not found',
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
        if($transport->delete()){
            return response()->json([
                "message" => "Transport deleted successfully"
            ], 201);
        }else{
            return response()->json(["message" => "Delete Transport error"], 500);
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
