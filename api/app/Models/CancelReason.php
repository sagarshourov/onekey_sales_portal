<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancelReason extends Model 
{

    protected $table = 'cancel_reason';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}