<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Hash, Mail, Validator, Session, JWTAuth, JWTFactory, Storage;

use App\Model\Users\Portofolios;

class CA_portofolio extends Controller
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
      'data' => Portofolios::all(),
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
    foreach (Portofolios::formValidation() as $key => $value) {
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
    $portofolio = new Portofolios($request->all());
    $portofolio->save();

    // Insert Image
    if($request->hasFile('picture')){
      if (!Storage::exists('public/images/users/'.$portofolio->user_id.'/'.$portofolio->id)) {
        Storage::makeDirectory('public/images/users/'.$portofolio->user_id.'/'.$portofolio->id, 0777);
      }
      $path = Storage::putFile('public/images/users/'.$portofolio->user_id.'/'.$portofolio->id, $request->file('picture'));
      $path = explode('/',$path);
      $insertFile = Portofolios::where('id',$portofolio->id)->first();
      $insertFile->picture = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4]. '/' . $path[5] ;
      $insertFile->save();
    }

    // Return
    return response()->json([
      'status'=> true,
      'data' => [
        'portofolio' => Portofolios::findOrFail($portofolio->id)
      ],
      'message' => "Add portofolio successfully!"
    ], 200);

  }


  public function show($id)
  {
    try {
      // Return
      return response()->json([
        'status'=> TRUE,
        'data' => [
          'portofolio' => Portofolios::findOrFail($id)
        ],
        'message' => "Your portofolio."
      ], 200);
    } catch (\Exception $e) {
      // Return
      return response()->json([
        'status'=> FALSE,
        'data' => null,
        'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "portofolio with id " . $id." does not exist." : $e->getMessage()
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
    foreach (Portofolios::formValidation() as $key => $value) {
      if(isset($request[$key]) && !empty($request[$key]) ){
        $rules[$key] = $value;
        if($key != "picture"){
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
      $portofolio = Portofolios::findOrFail($id);
      $portofolio->update($data);

      // Delete Image Team Before if there an image files
      if($request->hasFile('picture')){
        $pathImage = 'public/'.$portofolio['picture'];
        if(!empty($portofolio->picture)){
          Storage::delete($pathImage);
        }
        if (!Storage::exists('public/images/users/'.$portofolio->user_id.'/'.$portofolio->id)) {
          Storage::makeDirectory('public/images/users/'.$portofolio->user_id.'/'.$portofolio->id, 0777);
        }
        $path = Storage::putFile('public/images/users/'.$portofolio->user_id.'/'.$portofolio->id, $request->file('picture'));
        $path = explode('/',$path);
        $insertFile = Portofolios::where('id',$portofolio->id)->first();
        $insertFile->picture = $path[1] . '/' . $path[2] . '/' . $path[3]. '/' . $path[4]. '/' . $path[5] ;
        $insertFile->save();
      }
      return response()->json([
        'status'=> true,
        'data' => Portofolios::findOrFail($id),
        'message' => "Update portofolio successfully!"
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "portofolio with id " . $id." does not exist." : $e->getMessage()
      ], 200);
    }

  }

  public function destroy($id)
  {

    try {
      $portofolio = Portofolios::findOrFail($id);
      // Delete images on Storage
      $pathImage = 'public/'.$portofolio['picture'];
      Storage::delete($pathImage);
      $portofolio->delete();
      return response()->json([
        'status'=> true,
        'data' => null,
        'message' => "Delete portofolio successfully!"
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "portofolio with id " . $id." does not exist." : $e->getMessage()
      ], 200);
    }

  }
}
