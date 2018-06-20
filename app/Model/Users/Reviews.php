<?php

namespace App\Model\Users;

use Illuminate\Database\Eloquent\Model;

use App\Model\Company\Companies;
use App\Model\Users\User;

use App\Rules\ValidCompany;
use App\Rules\ValidUser;

class Reviews extends Model
{
  protected $fillable = ['company_id','user_id','review','rating'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'company_id' => 'Company',
      'user_id' => 'User',
      'review' => 'Review',
      'rating' => 'Rating'
    ];
  }
  public static function formValidation(){
    return [
      'company_id' => ['required', new ValidCompany],
      'user_id' => ['required', new ValidUser],
      'review' => 'required',
      'rating' => 'required'
    ];
  }
  
  public function company()
  {
    return $this->belongsTo(Companies::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

}
