<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaritalStatus extends Model
{

    protected $table = 'marital_status';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];



}
