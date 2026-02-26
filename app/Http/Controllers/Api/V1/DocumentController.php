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
    // $request->user_id = $validate_user['userId'];
    $data = $request->validated();
    $data['user_id'] = $validate_user['userId'];
    $document = Document::create($data);
    return response()->json([
        "message" => "Document created successfully",
        "data" => $document,
    ]);
  }

  public function update(DocumentUpdateFormRequest $request, $id)
  {
    $validate_user = $this->validateUser($request->bearerToken());
    $document = Document::findOrFail($id);
    $data = $request->validated();
    $data['user_id'] = $validate_user['userId'];
    $documentUpdated = $document->update($data);
    $driver = Document::findOrFail($id)->with('vehicles');
    return response()->json([
        "message" => "Driver updated successfully",
        "data" => $document,
    ]);
  }

  public function destroy(Request $request, $id){
    $validate_user = $this->validateUser($request->bearerToken());
    $document = Document::findOrFail($id);
    $document->delete();
    return response()->json([
        "message" => "Document deleted successfully"
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
        return ['token' => $token,
                'userId' => $tokenFromRequest->tokenable_id];
    }
}
