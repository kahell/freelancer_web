<?php

namespace App\Model\Packages;

use Illuminate\Database\Eloquent\Model;

use App\Model\Users\User;

class Ranks extends Model
{
  protected $fillable = ['name','points'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'name' => 'Name',
      'points' => 'Point'
    ];
  }
  public static function formValidation(){
    return [
      'name' => 'required|min:3|max:255',
      'points' => 'required'
    ];
  }

  public function user()
  {
    return $this->hasOne(User::class,'rank_id');
  }

}
