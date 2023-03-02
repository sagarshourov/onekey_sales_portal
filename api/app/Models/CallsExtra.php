<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallsExtra extends Model
{

  protected $table = 'calls_extra';
  public $timestamps = true;

  use SoftDeletes;

  //  protected $dates = ['deleted_at'];

  protected $fillable = [
    'id', 'call_id', 'field', 'value', 'user_id'
  ];


  public function user()
  {
    return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name','is_admin');
  }
}
