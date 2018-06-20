<?php

namespace App\Model\Company;

use Illuminate\Database\Eloquent\Model;

use App\Model\Company\Projects;
use App\Model\Transactions\Transactions;
use App\Model\Users\User;
use App\Model\Company\Contracts;

use App\Rules\ValidProject;
use App\Rules\ValidUser;

class Bids extends Model
{
  protected $fillable = ['project_id' ,'user_id' ,'days','salary'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'project_id' => 'Project',
      'user_id' => 'User',
      'days' => 'Days',
      'salary' => 'Salary',
    ];
  }
  public static function formValidation(){
    return [
      'project_id' => ['required', new ValidProject],
      'user_id' => ['required', new ValidUser],
      'days' => 'required',
      'salary' => 'required'
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

  public function contract()
  {
    return $this->hasOne(Contracts::class,'bid_id');
  }

  public function transaction()
  {
    return $this->hasOne(Transactions::class,'bid_id');
  }

}
