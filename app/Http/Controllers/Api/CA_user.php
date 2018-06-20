<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Users\User;
use App\Model\Packages\Ranks;
use Validator, Storage;

use App\Rules\ValidPhone;
use App\Rules\ValidRank;

class CA_user extends Controller
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
          'data' => User::with(['portofolio','transaction','review','bid','company'])->get(),
          'message' => 'Success'
        ],200);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
      //Validation
      $validator = Validator::make($request->all(), [
        'username' => 'required|unique:users|between:4,20',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|between:6,25|confirmed',
        'name' => 'required|max:191',
        'gender' => 'required',
        'bod' => 'required',
        'country' => 'required',
        'phone_number' => ['required', new ValidPhone],
        'rank_id' => ['required', new ValidRank]
      ]);

      if($validator->fails())
      {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => $validator->errors()->first()
        ], 200);
      }

      // Get Rank
      $rank = Ranks::findOrFail($request->rank_id)->first();

      // Initialize
      $user = new User();
      $user->username = $request->username;
      $user->email = $request->email;
      $user->name = $request->name;
      $user->phone_number = $request->phone_number;
      $user->gender = $request->gender;
      $user->bod = $request->bod;
      $user->rank_id = $request->rank_id;
      $user->points = $rank->points;
      $user->password = bcrypt($request->password);
      $user->save();

      return response()
      ->json([
        'status' => true,
        'data' => null,
        'message' => 'Create User Successfully!.'
      ],200);

    }


    public function show($id)
    {
      try {
        // Return
        return response()->json([
          'status'=> TRUE,
          'data' => [
            'user' => User::with(['portofolio','transaction','review','bid','company'])->where(['id' => $id])->first()
          ],
          'message' => "Your user."
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
      foreach (User::formValidation() as $key => $value) {
        if(isset($request[$key]) && !empty($request[$key]) ){
          $rules[$key] = $value;
          if($key != "avatar" && $key != "curicullum_vitae"){
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
        $user = User::findOrFail($id);
        $user->update($data);

        // Delete Image Team Before if there an image files
        if($request->hasFile('avatar')){
          $pathImage = 'public/'.$user['avatar'];
          if(!empty($user->avatar)){
            Storage::delete($pathImage);
          }
          //Insert new Image
          if (!Storage::exists('public/images/users/'.$user->id)) {
              Storage::makeDirectory('public/images/users/'.$user->id, 0777);
          }
          $path = Storage::putFile('public/images/users/'.$user->id, $request->file('avatar'));
          $path = explode('/',$path);
          $insertFile = User::where('id',$user->id)->first();
          $insertFile->avatar = $path[1] . '/' . $path[2] . '/' . $path[3] .'/' . $path[4];
          $insertFile->save();
        }

        // CV
        if($request->hasFile('curicullum_vitae')){
          $pathImage = 'public/'.$user['curicullum_vitae'];
          if(!empty($user->curicullum_vitae)){
            Storage::delete($pathImage);
          }
          //Insert new Image
          if (!Storage::exists('public/images/users/'.$user->id)) {
              Storage::makeDirectory('public/images/users/'.$user->id, 0777);
          }
          $path = Storage::putFile('public/images/users/'.$user->id, $request->file('curicullum_vitae'));
          $path = explode('/',$path);
          $insertFile = User::where('id',$user->id)->first();
          $insertFile->curicullum_vitae = $path[1] . '/' . $path[2] . '/' . $path[3] .'/' . $path[4];
          $insertFile->save();
        }

        return response()->json([
          'status'=> true,
          'data' => User::with(['portofolio','transaction','review','bid','company'])->where(['id' => $id])->first(),
          'message' => "Update users successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "User with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }

    public function destroy($id)
    {

      try {
        $user = User::findOrFail($id);
        // Delete images on Storage
        $pathImage = 'public/'.$user['avatar'];
        Storage::delete($pathImage);
        $user->delete();
        return response()->json([
          'status'=> true,
          'data' => null,
          'message' => "Delete user successfully!"
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => (strpos($e->getMessage(), 'No query results for model') !== false)? "user with id " . $id." does not exist." : $e->getMessage()
        ], 200);
      }

    }
}
