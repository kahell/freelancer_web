<?php

namespace App\Model\Company;

use Illuminate\Database\Eloquent\Model;

use App\Model\Company\Bids;

use App\Rules\ValidBid;


class Contracts extends Model
{
  protected $fillable = ['bid_id','status','file','link_projects','description','date_start','date_ended'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'bid_id' => 'Bid',
      'status' => 'Status',
      'file' => 'File',
      'link_projects' => 'Link Projects',
      'description' => 'Description',
      'date_start' => 'Date Start',
      'date_ended' => 'Date Ended'
    ];
  }
  public static function formValidation(){
    return [
      'bid_id' => ['required', new ValidBid],
      'status' => 'required',
      'file' => 'required|mimes:doc,docx,pdf,jpeg,png,jpg,rar,zip',
      'link_projects' => 'required',
      'description' => 'required',
      'date_start' => 'required',
      'date_ended' => 'required'
    ];
  }

  public function bid()
  {
    return $this->belongsTo(Bids::class);
  }

}
