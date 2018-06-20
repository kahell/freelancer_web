<?php

namespace App\Model\Users;

use Illuminate\Database\Eloquent\Model;

use App\Model\Company\Companies;
use App\Model\Users\User;
use App\Model\Company\Projects;

use App\Rules\ValidCompany;
use App\Rules\ValidUser;
use App\Rules\ValidProject;

class Reviews extends Model
{
  protected $fillable = ['project_id','company_id','user_id','review','rating'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'project_id' => 'Project',
      'company_id' => 'Company',
      'user_id' => 'User',
      'review' => 'Review',
      'rating' => 'Rating'
    ];
  }
  public static function formValidation(){
    return [
      'project_id' => ['required', new ValidProject],
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

  public function project()
  {
    return $this->belongsTo(Projects::class);
  }

}
