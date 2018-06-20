<?php

namespace App\Model\Transactions;

use Illuminate\Database\Eloquent\Model;

use App\Model\Company\Projects;
use App\Model\Transactions\Transactions;
use App\Model\Users\User;

use App\Rules\ValidProject;
use App\Rules\ValidUser;
use App\Rules\ValidBid;

class Transactions extends Model
{
  protected $fillable = ['project_id','bid_id','user_id','method_payments','bank_account','salary_amount',
  'tax_amount','total_amount','date','status'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'project_id' => 'Project',
      'user_id' => 'User',
      'bid_id' => 'Bid',
      'method_payments' => 'Method Payments',
      'bank_account' => 'Bank Account',
      'salary_amount' => 'Salary Amount',
      'tax_amount' => 'Tax Amount',
      'date' => 'Date',
      'status' => 'Status'
    ];
  }
  public static function formValidation(){
    return [
      'project_id' => ['required', new ValidProject],
      'user_id' => ['required', new ValidUser],
      'bid_id' => ['required', new ValidBid],
      'method_payments' => 'required',
      'bank_account' => 'required',
      'salary_amount' => 'required',
      'tax_amount' => 'required',
      'date' => 'required'
    ];
  }

  public function project()
  {
    return $this->belongsTo(Projects::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function bid()
  {
    return $this->belongsTo(Bids::class);
  }
}
