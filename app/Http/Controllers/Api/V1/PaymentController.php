<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PaymentFormRequest;
use Illuminate\Http\Request;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Resources\ShipmentResource;
use App\Models\Payment;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
class PaymentController extends Controller implements HasMiddleware
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
        $payment = Payment::with('shipment')->get();
        return['payment' => $payment];
        // return DriverReource::collection(Shipment::all());
    }

    public function store(PaymentFormRequest $request)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        $data = $request->validated();
        try{
            $payment = Payment::create($data);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'message' => 'Payment not found',
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
            "message" => "Payment created successfully",
            "data" => $payment,
        ], 201);
    }

    public  function update(PaymentFormRequest $request, $id)
    {
            $validate_user = $this->validateUser($request->bearerToken());
            $data = $request->validated();
                
            try{
                $payment = Payment::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'message' => 'Payment not found',
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
            if($payment->update($data)){
                $payment = Payment::with('shipment')->findOrFail($id);
                return response()->json([
                    "message" => "Payment",
                    "data" => $payment,
                ],201);
            }else{
                return response()->json(['message' => "Update error"],500);
            }
    }

    public function destroy(Request $request, $id)
    {
        $validate_user = $this->validateUser($request->bearerToken());
        try{
            $payment = Payment::findOrFail($id);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    "message" => "Payment not found",

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
            if($payment->delete()){
                return response()->json([
                    "message" => "Payment deleted successfully"
                ], 500);
            }else{
                return response()->json(["message" => "Delete Payment error"], 404);
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