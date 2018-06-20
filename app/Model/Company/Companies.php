<?php

namespace App\Model\Company;

use Illuminate\Database\Eloquent\Model;

use App\Model\Users\User;
use App\Rules\ValidUser;

class Companies extends Model
{
  protected $fillable = ['user_id', 'name','description','industry','logo','country','address','link_website','culture'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'user_id' => 'Users',
      'name' => 'Name',
      'description' => 'Description',
      'industry' => 'Industry',
      'logo' => 'Logo',
      'country' => 'Country',
      'address' => 'Address',
      'link_website' => 'Web',
      'culture' => 'Culture',
    ];
  }
  public static function formValidation(){
    return [
      'user_id' => ['required', new ValidUser],
      'name' => 'required|min:3|max:255',
      'description' => 'required',
      'industry' => 'required',
      'logo' => 'required|max:10000|mimes:jpeg,png,jpg',
      'country' => 'required',
      'address' => 'required',
      'link_website' => 'required',
      'culture' => 'required'
    ];
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function project()
  {
    return $this->hasMany(Projects::class,'company_id');
  }

}
