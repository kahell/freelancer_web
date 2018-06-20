<?php

namespace App\Model\Users;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Rules\ValidPhone;
use App\Rules\ValidRank;

use App\Model\Company\Companies;
use App\Model\Transactions\Transactions;
use App\Model\Company\Bids;
use App\Model\Users\Portofolios;
use App\Model\Users\Reviews;
use App\Model\Packages\Ranks;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = ['name', 'gender','avatar','address','bod','phone_number','username','email','country',
    'wallet','bank_account','skills','curicullum_vitae','salary','points','rank_id'];
    protected $hidden = ['password', 'remember_token'];

    public static function initialize(){
      return [
        'username'=> 'Username','email'=> 'Email', 'password'=> 'Password', 'name' => 'Name',
        'gender' => 'Gender','avatar' => 'Avatar','address' => 'Address','bod' => 'Birth of Date','phone_number' => 'Phone',
        'country' => 'Country','wallet' => 'Wallet','bank_account' => 'Bank Account', 'skills' => 'Skills', 'curicullum_vitae' => 'curicullum Vitae',
        'salary' => 'Salary', 'points' => 'Point', 'rank_id' => 'Rank',
      ];
    }

    public function check_username(){
      return "usertest";
    }

    public static function formValidation(){
      return [
        'name' => 'required|min:3|max:255',
        'gender' => 'required',
        'avatar' => 'required',
        'address' => 'required',
        'username' => 'required|unique:users|alpha_num|between:4,20',
        'password' => 'required|between:6,25|',
        'email' =>'required|email|unique:users|max:255',
        'bod' => 'required',
        'phone_number' => ['required', new ValidPhone],
        'country' => 'required',
        'wallet' => 'required',
        'bank_account' => 'required',
        'skills' => 'required',
        'curicullum_vitae' => 'required',
        'salary' => 'required',
        'points' => 'required',
        'rank_id' => ['required', new ValidRank]
      ];
    }

    public function company()
    {
        return $this->hasMany(Companies::class ,'user_id');
    }

    public function rank()
    {
        return $this->belongsTo(Ranks::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transactions::class, 'user_id');
    }

    public function portofolio()
    {
        return $this->hasMany(Portofolios::class, 'user_id');
    }

    public function review()
    {
        return $this->hasMany(Reviews::class, 'user_id');
    }

    public function bids()
    {
        return $this->hasMany(Bids::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
