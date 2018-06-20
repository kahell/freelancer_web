<?php

namespace App\Model\Users;

use Illuminate\Database\Eloquent\Model;

use App\Model\Users\User;

use App\Rules\ValidUser;

class Portofolios extends Model
{
  protected $fillable = ['user_id','picture'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'user_id' => 'User',
      'picture' => 'Picture'
    ];
  }
  public static function formValidation(){
    return [
      'user_id' => ['required', new ValidUser],
      'picture' => 'required|max:10000|mimes:jpeg,png,jpg',
    ];
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
