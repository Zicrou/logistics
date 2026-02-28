<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentFormRequest;
use App\Http\Requests\DocumentUpdateFormRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
class DocumentController extends Controller implements HasMiddleware
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
    $document = Document::where('user_id', $validate_user['userId'])->with('shipment')->with('user')->get();
    return['document' => $document];
        // return DriverReource::collection(Shipment::all());
    
  }  

  public function store(DocumentFormRequest $request){
    $validate_user = $this->validateUser($request->bearerToken());
    $data = $request->validated();
    $data['user_id'] = $validate_user['userId'];
    try{
        $document = Document::create($data);
    }catch(ModelNotFoundException $e){
        return response()->json([
            'message' => 'Document not found',
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
            "message" => "Document created successfully",
            "data" => $document,
        ], 201);
  }

  public function update(DocumentUpdateFormRequest $request, $id)
  {
    $validate_user = $this->validateUser($request->bearerToken());
    $data = $request->validated();
    try{
        $document = Document::findOrFail($id);
    }catch(ModelNotFoundException $e){
        return response()->json([
            'message' => 'Document not found',
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
    $data['user_id'] = $validate_user['userId'];
    if($document->update($data)){
        $document = Document::with('shipment')->findOrFail($id);
        return response()->json([
            "message" => "Driver updated successfully",
            "data" => $document,
        ],201);
    }else{
        return response()->json(['message' => "Update error"],500);
    }
  }

  public function destroy(Request $request, $id){
    $validate_user = $this->validateUser($request->bearerToken());
    try{
        $document = Document::findOrFail($id);
    }catch(ModelNotFoundException $e){
        return response()->json([
            'message' => 'Document not found',
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
    if($document->delete()){
        return response()->json([
            "message" => "Document deleted successfully"
        ], 201);
    }else{
        return response()->json(["message" => "Delete Document error"], 500);
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
        return ['token' => $token,
                'userId' => $tokenFromRequest->tokenable_id];
    }
}
