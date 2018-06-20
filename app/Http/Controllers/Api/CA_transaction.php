<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Company\Projects;
use App\Model\Company\Bids;
use App\Model\Users\User;
use App\Model\Company\Companies;
use App\Model\Company\Contracts;
use App\Model\Transactions\Transactions;
use App\Model\Packages\Package_ranks;
use App\Model\Packages\Ranks;

use App\Rules\ValidProject;
use App\Rules\ValidUser;
use App\Rules\ValidBid;
use App\Rules\ValidPackagesRank;

use Carbon\Carbon;
use Validator, Storage, JWTAuth, Auth;

class CA_transaction extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt');
  }

  public function payment(Request $request)
  {
    //Validation
    $validator = Validator::make($request->all(), [
      'project_id' => ['required', new ValidProject],
      'bid_id' => ['required', new ValidBid],
      'method_payments' => 'required',
      'bank_account' => 'required'
    ]);

    if($validator->fails())
    {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => $validator->errors()->first()
      ], 200);
    }

    // initialize

    $bids = Bids::findOrFail($request->bid_id)->first();
    $user = User::findOrFail($bids->user_id)->first();
    $project = Projects::findOrFail($request->project_id);
    $self = JWTAuth::parseToken()->authenticate();

    $salary = $bids->salary;
    $tax = $salary * 0.1;
    $total_salary = $salary + $tax;
    $wallet =  $self->wallet - $total_salary;

    // Check wallet
    if($request->method_payments == 1){
      $user_self = User::findOrFail($self->id);
      if($wallet < 0){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Your wallet is not enaugh."
        ], 200);
      }
      $user_self->wallet = $wallet;
      $user_self->save();
    }

    // Update user wallet
    $user->wallet = $user->wallet + $salary;
    $user->save();

    // Insert Transactions
    $transaction = new Transactions();
    $transaction->project_id = $request->project_id;
    $transaction->user_id = $bids->user_id;
    $transaction->bid_id = $request->bid_id;
    $transaction->method_payments = $request->method_payments;
    $transaction->bank_account = $request->bank_account;
    $transaction->salary_amount = $salary;
    $transaction->tax_amount = $tax;
    $transaction->total_amount = $total_salary;
    $transaction->status = 1;
    $transaction->date = Carbon::now('Asia/Jakarta');
    $transaction->save();

    // Return
    return response()->json([
      'status'=> true,
      'data' => [
        'transaction' => Transactions::findOrFail($transaction->id)
      ],
      'message' => "Add transaction successfully!"
    ], 200);

  }

  public function buy_rank(Request $request)
  {
    //Validation
    $validator = Validator::make($request->all(), [
      'package_rank_id' => ['required', new ValidPackagesRank],
      'method_payments' => 'required',
      'bank_account' => 'required'
    ]);

    if($validator->fails())
    {
      return response()->json([
        'status'=> false,
        'data' => null,
        'message' => $validator->errors()->first()
      ], 200);
    }

    // initialize
    $package_rank = Package_ranks::findOrFail($request->package_rank_id);
    $self = JWTAuth::parseToken()->authenticate();
    $user_self = User::findOrFail($self->id);

    $tax = $package_rank->prices * 0.1;
    $total_amount = $package_rank->prices + $tax;
    $wallet =  $self->wallet - $total_amount;

    // Check wallet
    if($request->method_payments == 1){
      if($wallet < 0){
        return response()->json([
          'status'=> false,
          'data' => null,
          'message' => "Your wallet is not enaugh."
        ], 200);
      }
    }

    $user_self->wallet = $wallet;
    $user_self->rank_id = $package_rank->rank_id;
    $user_self->save();

    // Insert Transactions
    $transaction = new Transactions();
    $transaction->package_rank_id = $request->package_rank_id;
    $transaction->user_id = $self->id;
    $transaction->method_payments = $request->method_payments;
    $transaction->bank_account = $request->bank_account;
    $transaction->tax_amount = $tax;
    $transaction->total_amount = $total_amount;
    $transaction->status = 1;
    $transaction->date = Carbon::now('Asia/Jakarta');
    $transaction->save();

    // Return
    return response()->json([
      'status'=> true,
      'data' => [
        'transaction' => Transactions::findOrFail($transaction->id)
      ],
      'message' => "Buy Package successfully!"
    ], 200);

  }
}
