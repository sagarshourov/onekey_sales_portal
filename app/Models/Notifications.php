<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model
{

    protected $table = 'notifications';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'type', 'content', 'is_read',  'user_id', 'call_id', 'to_id', 'admin_id', 'note'
    ];


    public function types()
    {
        return $this->hasOne(NotiType::class, 'id', 'type')->select('id', 'title');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name');
    }
    public function admin()
    {
        return $this->hasOne(User::class, 'id', 'admin_id')->select('id', 'first_name', 'last_name');
    }

    public function receiver()
    {
        return $this->hasOne(User::class, 'id', 'to_id')->select('id', 'first_name', 'last_name');
    }
}
