<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash, Mail, Validator, Session, JWTAuth, JWTFactory;
use Illuminate\Support\Facades\Auth;

use App\Model\Users\User;
use App\Model\Packages\Ranks;

use App\Rules\ValidPhone;
use App\Rules\ValidRank;

class CA_authController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt',['except' => ['login','register','logout']]);
  }
  public function register(Request $request)
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
      'message' => 'Registered Successfully!.'
    ],200);
  }

  public function login(Request $request)
  {
    //Validation
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required'
    ]);

    if($validator->fails())
    {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => $validator->errors()->first()
      ], 200);
    }

    $user = User::where('username', $request->username)->first();

    if($user && Hash::check($request->password, $user->password)){
      $user->save();
      $credentials = request(['username','password']);
      $token = JWTAuth::attempt($credentials);
      Session::put('token', $token);
      return response()
      ->json([
        'status' => true,
        'data' => [
          'token' => $token
        ],
        'message' => 'Login successfully!'
      ],200);
    }

    return response()
    ->json([
      'status' => FALSE,
      'data' => null,
      'message' => 'Provided username and password does not match!'
    ],200);
  }

  public function me()
  {
    return response()->json(JWTAuth::user());
  }

  public function logout(Request $request)
  {
    $header = $request->header('Authorization');
    JWTAuth::invalidate($header);
    auth()->logout();
    return response()->json(
      [
        'status' => true,
        'data' => [
          'token' => null
        ],
        'message' => 'Successfully logged out'
      ]
    );
  }

  public function refresh()
  {
    return $this->respondWithToken(JWTAuth::refresh());
  }

  public function payload()
  {
    return JWTAuth::payload();
  }

  protected function respondWithToken($token)
    {
        return [
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }
}
