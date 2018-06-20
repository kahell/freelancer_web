<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Company\Projects;
use App\Model\Company\Companies;
use Validator, Storage;

class CA_project extends Controller
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
          'data' => Projects::all(),
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
      foreach (Projects::formValidation() as $key => $value) {
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
      $project = new Projects($request->all());
      $project->save();

      // Insert Image
      if($request->hasFile('file_beverage')){
        $file = $request->file('file_beverage');
        if (!Storage::exists('public/images/company/'.$project->company_id.'/project/')) {
            Storage::makeDirectory('public/images/company/'.$project->company_id.'/project/', 0777);
        }
        $path = Storage::putFile('public/images/company/'.$project->company_id.'/project/', $request->file('file_beverage'));
        $path = explode('/',$path);
        $insertFile = Projects::where('id',$project->id)->first();
        $insertFile->file_beverage = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4]. '/' . $path[5]. '/' . $path[6];
        $insertFile->save();
      }

      // Return
      return response()->json([
        'status'=> true,
        'data' => [
          'project' => Projects::findOrFail($project->id)
        ],
        'message' => "Add project successfully!"
      ], 200);

    }


    public function show($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'project' => Projects::with(['bids'])->where(['id' => $id])->first()
          ],
          'message' => "Your project."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Project with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }
    }

    public function showByCompany($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'project' => Projects::with(['bids'])->where(['company_id' => $id])->get()
          ],
          'message' => "Your project."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Project with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }
    }

    public function showByPublish()
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'project' => Projects::with(['bids'])->where(['status' => 1])->get()
          ],
          'message' => "Publish project."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Project with id " . $id." does not exist." : $e->getMessage()
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
      foreach (Projects::formValidation() as $key => $value) {
        if(isset($request[$key]) && !empty($request[$key]) ){
          $rules[$key] = $value;
          if($key != "file_beverage"){
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
        $project = Projects::findOrFail($id);
        $project->update($data);

        // Delete Image Team Before if there an image files
        if($request->hasFile('file_beverage')){
          $pathImage = 'public/'.$project['file_beverage'];
          if(!empty($project->file_beverage)){
            Storage::delete($pathImage);
          }

          if (!Storage::exists('public/images/company/'.$project->company_id.'/project/')) {
              Storage::makeDirectory('public/images/company/'.$project->company_id.'/project/', 0777);
          }
          $path = Storage::putFile('public/images/company/'.$project->company_id.'/project/', $request->file('file_beverage'));
          $path = explode('/',$path);
          $insertFile = Projects::where('id',$project->id)->first();
          $insertFile->file_beverage = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4]. '/' . $path[5]. '/' . $path[6];
          $insertFile->save();
        }
        return response()->json([
          'status'=> true,
          'data' => Projects::findOrFail($id),
          'message' => "Update project successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "project with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }

    public function destroy($id)
    {

      try {
        $project = Projects::findOrFail($id);
        // Delete images on Storage
        $pathImage = 'public/'.$project['file_beverage'];
        Storage::delete($pathImage);
        $project->delete();
        return response()->json([
          'status'=> true,
          'data' => null,
          'message' => "Delete project successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "project with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }
}
