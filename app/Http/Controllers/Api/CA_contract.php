<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Company\Projects;
use App\Model\Company\Bids;
use App\Model\Company\Companies;
use App\Model\Company\Contracts;
use Validator, Storage, JWTAuth, Auth;

use Carbon\Carbon;

class CA_contract extends Controller
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
          'data' => Contracts::all(),
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
      foreach (Contracts::formValidation() as $key => $value) {
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

      // Check Contract User
      $bids = Bids::where(['id' => $request->bid_id])->first();
      $project = Projects::findOrFail($bids->project_id)->company->user;
      $contract = Contracts::where(['bid_id' => $request->bid_id])->first();
      $self = JWTAuth::parseToken()->authenticate();
      // Check If User Bidding Self
      if($self->id == $project->id){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Sorry, you canot contract job create by your self."
        ], 200);
      }
      if(!empty($contract)){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Sorry, you are already contract."
        ], 200);
      }

      // Insert Product
      $contract = new Contracts();
      $contract->bid_id = $request->bid_id;
      $contract->status = 2;
      $contract->save();

      // Insert Image
      if($request->hasFile('file')){
        $bids = Bids::where(['id' => $contract->bid_id])->first();
        $project = Projects::where(['id' => $bids->project_id])->first();

        if (!Storage::exists('public/images/company/'.$project->company_id.'/project/'.$bids->project_id.'/contract/')) {
            Storage::makeDirectory('public/images/company/'.$project->company_id.'/project/'.$bids->project_id.'/contract/', 0777);
        }
        $path = Storage::putFile('public/images/company/'.$project->company_id.'/project/'.$bids->project_id.'/contract/', $request->file('file'));
        $path = explode('/',$path);
        $insertFile = Contracts::where('id',$contract->id)->first();
        $insertFile->file = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4]. '/' . $path[5]. '/' . $path[6]. '/' . $path[8];
        $insertFile->save();
      }

      // Return
      return response()->json([
        'status'=> true,
        'data' => [
          'contract' => Contracts::findOrFail($contract->id)
        ],
        'message' => "Add contract successfully!"
      ], 200);

    }


    public function show($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'contract' => Contracts::with(['bid'])->where(['id' => $id])->first()
          ],
          'message' => "Your contract."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Contract with id " . $id." does not exist." : $e->getMessage()
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
      foreach (Contracts::formValidation() as $key => $value) {
        if(isset($request[$key]) && !empty($request[$key]) ){
          $rules[$key] = $value;
          if($key != "file"){
            $data[$key] = $request[$key];
          }
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
        $contract = Contracts::findOrFail($id);
        $contract->update($data);

        // Delete Image Team Before if there an image files
        if($request->hasFile('file')){
          $bids = Bids::where(['id' => $contract->bid_id])->first();
          $project = Projects::where(['id' => $bids->project_id])->first();
          $pathImage = 'public/'.$contract['file'];
          if(!empty($contract->file)){
            Storage::delete($pathImage);
          }

          if (!Storage::exists('public/images/company/'.$project->company_id.'/project/'.$bids->project_id.'/contract/')) {
              Storage::makeDirectory('public/images/company/'.$project->company_id.'/project/'.$bids->project_id.'/contract/', 0777);
          }
          $path = Storage::putFile('public/images/company/'.$project->company_id.'/project/'.$bids->project_id.'/contract/', $request->file('file'));
          $path = explode('/',$path);
          $insertFile = Contracts::where('id',$contract->id)->first();
          $insertFile->file = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4]. '/' . $path[5]. '/' . $path[6]. '/' .
          $path[8];
          $insertFile->save();
        }

        return response()->json([
          'status'=> true,
          'data' => Contracts::findOrFail($id),
          'message' => "Update contract successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "contract with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }

    public function start(Request $request, $id)
    {
      //validation
      $rules = [];
      $data = [];
      foreach (Contracts::formValidation() as $key => $value) {
        if(isset($request[$key]) && !empty($request[$key]) ){
          $rules[$key] = $value;
          if($key != "file"){
            $data[$key] = $request[$key];
          }
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
        $contract = Contracts::findOrFail($id);
        $bids = Bids::where(['id' => $contract->bid_id])->first();
        $data['date_start'] = Carbon::now('Asia/Jakarta');
        $data['date_ended'] = Carbon::now('Asia/Jakarta')->addDays($bids->days);
        $contract->update($data);

        return response()->json([
          'status'=> true,
          'data' => Contracts::findOrFail($id),
          'message' => "Update contract successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "contract with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }

    public function destroy($id)
    {

      try {
        $contract = Contracts::findOrFail($id);
        // Delete images on Storage
        $pathImage = 'public/'.$contract['file'];
        Storage::delete($pathImage);
        $contract->delete();
        return response()->json([
          'status'=> true,
          'data' => null,
          'message' => "Delete contract successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "contract with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }
}
