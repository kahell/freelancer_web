<?php

namespace App\Model\Company;

use Illuminate\Database\Eloquent\Model;

use App\Rules\ValidCompany;
use App\Model\Company\Companies;
use App\Model\Company\Bids;
use App\Model\Transactions\Transactions;

class Projects extends Model
{
  protected $fillable = ['company_id','title','description','skill_required','range_salary','project_type','status','file_beverage'];
  protected $hidden = ['created_at', 'updated_at'];

  public static function initialize(){
    return [
      'company_id' => 'Company',
      'title' => 'Title',
      'description' => 'Description',
      'skill_required' => 'Skill',
      'range_salary' => 'Range Salary',
      'project_type' => 'Project Type',
      'status' => 'Status',
      'file_beverage' => 'File Beverage'
    ];
  }
  public static function formValidation(){
    return [
      'company_id' => ['required', new ValidCompany],
      'title' => 'required',
      'description' => 'required',
      'skill_required' => 'required',
      'range_salary' => 'required',
      'project_type' => 'required',
      'address' => 'required',
      'status' => 'required',
      'file_beverage' => 'required|max:10000|mimes:doc,docx,pdf,jpeg,png,jpg'
    ];
  }

  public function company()
  {
    return $this->belongsTo(Companies::class);
  }

  public function bids()
  {
    return $this->hasMany(Bids::class,'project_id');
  }

  public function transaction()
  {
    return $this->hasMany(Transactions::class,'project_id');
  }

}
