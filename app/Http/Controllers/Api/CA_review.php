<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator, JWTAuth, JWTFactory, Storage;

use App\Model\Users\Reviews;
use App\Model\Company\Projects;

class CA_review extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt');
  }

  public function index()
  {
    return response()
    ->json([
      'status' => true,
      'data' => Reviews::all(),
      'message' => 'Success'
    ],200);
  }


  public function create()
  {
    //
  }


  public function store(Request $request)
  {
    //validation
    $rules = [];
    foreach (Reviews::formValidation() as $key => $value) {
      if(isset($request[$key]) && !empty($request[$key])){
        $rules[$key] = $value;
      }
    }

    $validator = Validator::make($request->all(), $rules);

    if($validator->fails())
    {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => $validator->errors()->first()
      ], 200);
    }

    // Insert Product
    $review = new Reviews($request->all());
    $review->save();

    // Return
    return response()->json([
      'status'=> true,
      'data' => [
        'review' => Reviews::with(['user','company','project'])->where(['id' => $review->id])->first()
      ],
      'message' => "Add review successfully!"
    ], 200);

  }


  public function show($id)
  {
    try {
      // Return
      return response()->json([
        'status'=> TRUE,
        'data' => [
          'review' => Reviews::with(['user','company','project'])->where(['id' => $id])->first()
        ],
        'message' => "Your review."
      ], 200);
    } catch (\Exception $e) {
      // Return
      return response()->json([
        'status'=> FALSE,
        'data' => null,
        'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "review with id " . $id." does not exist." : $e->getMessage()
      ], 200);
    }
  }

  public function edit($id)
  {
    //
  }

  public function update(Request $request, $id)
  {
    //validation
    $rules = [];
    $data = [];
    foreach (Reviews::formValidation() as $key => $value) {
      if(isset($request[$key]) && !empty($request[$key]) ){
        $rules[$key] = $value;
        $data[$key] = $request[$key];
      }
    }

    $validator = Validator::make($request->all(), $rules);

    if($validator->fails())
    {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => $validator->errors()->first()
      ], 200);
    }

    try {
      // Update Data
      $review = Reviews::findOrFail($id);
      $review->update($data);

      return response()->json([
        'status'=> true,
        'data' => Reviews::with(['user','company','project'])->where(['id' => $id])->first(),
        'message' => "Update review successfully!"
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "review with id " . $id." does not exist." : $e->getMessage()
      ], 200);
    }

  }

  public function destroy($id)
  {

    try {
      $review = Reviews::findOrFail($id);
      $review->delete();
      return response()->json([
        'status'=> true,
        'data' => null,
        'message' => "Delete review successfully!"
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "review with id " . $id." does not exist." : $e->getMessage()
      ], 200);
    }

  }
}
