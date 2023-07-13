<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallHistory extends Model
{

    protected $table = 'call_history';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'data', 'user_id', 'call_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name', 'is_admin');
    }
}
