<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Results extends Model 
{

    protected $table = 'results';
    public $timestamps = true;

    protected $fillable = [
        'id','title'
    ];

}