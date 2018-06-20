<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Company\Companies;
use Validator, Storage;

class CA_company extends Controller
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
          'data' => Companies::all(),
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
      foreach (Companies::formValidation() as $key => $value) {
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
      $company = new Companies($request->all());
      $company->save();

      // Insert Image
      if($request->hasFile('logo')){
        $file = $request->file('logo');
        if (!Storage::exists('public/images/company/'.$company->id)) {
            Storage::makeDirectory('public/images/company/'.$company->id, 0777);
        }
        $path = Storage::putFile('public/images/company/'.$company->id, $request->file('logo'));
        $path = explode('/',$path);
        $insertFile = Companies::where('id',$company->id)->first();
        $insertFile->logo = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4] ;
        $insertFile->save();
      }

      // Return
      return response()->json([
        'status'=> true,
        'data' => [
          'company' => Companies::findOrFail($company->id)
        ],
        'message' => "Add company successfully!"
      ], 200);

    }


    public function show($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'company' => Companies::findOrFail($id)
          ],
          'message' => "Your company."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Company with id " . $id." does not exist." : $e->getMessage()
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
            'company' => Companies::where(['user_id' => $id])->get()
          ],
          'message' => "Your company."
        ], 200);
      } catch (\Exception $e) {
        // Return
        return response()->json([
          'status'=> FALSE,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Company with id " . $id." does not exist." : $e->getMessage()
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
      foreach (Companies::formValidation() as $key => $value) {
        if(isset($request[$key]) && !empty($request[$key]) ){
          $rules[$key] = $value;
          if($key != "logo"){
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
        $company = Companies::findOrFail($id);
        $company->update($data);

        // Delete Image Team Before if there an image files
        if($request->hasFile('logo')){
          $pathImage = 'public/'.$company['logo'];
          if(!empty($company->logo)){
            Storage::delete($pathImage);
          }
          //Insert new Image
          $file = $request->file('logo');
          if (!Storage::exists('public/images/company/'.$company->id)) {
              Storage::makeDirectory('public/images/company/'.$company->id, 0777);
          }
          $path = Storage::putFile('public/images/company/'.$company->id, $request->file('logo'));
          $path = explode('/',$path);
          $insertFile = Companies::where('id',$company->id)->first();
          $insertFile->logo = $path[1] . '/' . $path[2] . '/' . $path[3] .'/' . $path[4];
          $insertFile->save();
        }
        return response()->json([
          'status'=> true,
          'data' => Companies::findOrFail($id),
          'message' => "Update company successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Company with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }

    public function destroy($id)
    {

      try {
        $company = Companies::findOrFail($id);
        // Delete images on Storage
        $pathImage = 'public/'.$company['logo'];
        Storage::delete($pathImage);
        $company->delete();
        return response()->json([
          'status'=> true,
          'data' => null,
          'message' => "Delete Company successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "Company with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }
}
