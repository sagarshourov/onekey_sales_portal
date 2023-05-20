<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model 
{

    protected $table = 'goal';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}