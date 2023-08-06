<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignEmployee extends Model 
{

    protected $table = 'assign_emp';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [

        'admin_id', 'user_id'

    ];



    public function users()
    {

        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name','email');
    }

    

}