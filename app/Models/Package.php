<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{

    protected $table = 'packages';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'title'
    ];


    public function Calls()
    {
        return $this->hasMany(Calls::class, 'package', 'id');
    }
}
