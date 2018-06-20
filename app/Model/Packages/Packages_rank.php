<?php

namespace App\Model\Packages;

use Illuminate\Database\Eloquent\Model;

use App\Model\Packages\Ranks;

use App\Rules\ValidRank;

class Packages_rank extends Model
{
  protected $fillable = ['rank_id','prices','month'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'rank_id' => 'Rank',
      'prices' => 'Prices',
      'month' => 'Month'
    ];
  }
  public static function formValidation(){
    return [
      'rank_id' => ['required', new ValidRank],
      'month' => 'required',
      'prices' => 'required'
    ];
  }

  public function rank()
  {
    return $this->belongsTo(Ranks::class);
  }
}
