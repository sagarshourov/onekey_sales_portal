<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WantToStudy extends Model 
{

    protected $table = 'want_to_study';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}