<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplyingFor extends Model 
{

    protected $table = 'applying_for';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}