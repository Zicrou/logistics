<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentFormRequest;
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

  }  

  public function store(DocumentFormRequest $request){
    $validate_user = $this->validateUser($request->bearerToken());
    $data = $request->validated();
    // $request->user_id = $validate_user['userId'];
    $data = $request->validated();
    $data['user_id'] = "da2f4eaa-3322-4b39-90a2-dcc59fd7042d";
    $document = Document::create($data);
    return response()->json([
        "message" => "Document created successfully",
        "data" => $document,
    ]);
  }

  public function update(Request $request)
  {

  }

  public function destroy(Request $request, $id){

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
