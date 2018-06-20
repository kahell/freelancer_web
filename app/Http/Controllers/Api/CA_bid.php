<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Company\Bids;
use App\Model\Company\Projects;
use App\Model\Users\User;
use Validator, Storage,JWTAuth, Auth;

class CA_bid extends Controller
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
          'data' => Bids::with(['contract'])->get(),
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
      foreach (Bids::formValidation() as $key => $value) {
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

      // Check points & Check Bidding User
      $user = User::findOrFail($request->user_id)->first();
      $bids = Bids::where(['project_id' => $request->project_id, 'user_id' => $request->user_id])->first();
      $project = Projects::findOrFail($request->project_id)->company->user;
      $self = JWTAuth::parseToken()->authenticate();
      // Check If User Bidding Self
      if($self->id == $project->id){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Sorry, you canot bidding job create by your self."
        ], 200);
      }
      if($user->points == 0){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Sorry your points is not enaugh."
        ], 200);
      }
      if(!empty($bids)){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Sorry, you are already biding."
        ], 200);
      }

      $user->points = ($user->points - 2);
      $user->save();

      // Insert Product
      $bid = new Bids($request->all());
      $bid->save();

      // Return
      return response()->json([
        'status'=> true,
        'data' => [
          'bid' => Bids::findOrFail($bid->id)
        ],
        'message' => "Add bid successfully!"
      ], 200);

    }


    public function show($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'bid' => Bids::with(['contract'])->where(['id' => $id])->first()
          ],
          'message' => "Your bid."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Bid with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }
    }

    public function showByUser($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'bid' => Bids::with(['contract'])->where(['user_id' => $id])->first()
          ],
          'message' => "Your bid."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "User with id " . $id." does not exist." : $e->getMessage()
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
      foreach (Bids::formValidation() as $key => $value) {
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
        $bid = Bids::findOrFail($id);
        $bid->update($data);

        return response()->json([
          'status'=> true,
          'data' => Bids::with(['contract'])->where(['id' => $id])->first(),
          'message' => "Update Bid successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Bid with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }

    public function destroy($id)
    {

      try {
        $bid = Bids::findOrFail($id);

        $bid->delete();
        return response()->json([
          'status'=> true,
          'data' => null,
          'message' => "Delete bid successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "bid with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }
}
