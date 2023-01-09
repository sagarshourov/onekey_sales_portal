<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calls extends Model
{

    protected $table = 'calls';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'first_name', 'last_name', 'phone_numbr', 'email',  'priority', 'note', 'follow_up_date', 'status', 'package', 'last_contact', 'age', 'gpa', 'last_status_date', 'last_status_notes', 'results', 'cancel_reason', 'feedbacks', 'user_id'
        ,'memo','f_results','referred_by','first_contact'
    ];

    public function extra()
    {
        return $this->hasMany(CallsExtra::class, 'call_id', 'id')->select();
    }


    public function results()
    {
        return $this->hasOne(Results::class, 'id', 'results')->select('id', 'title');
    }

    public function fresults()
    {
        return $this->hasOne(Results::class, 'id', 'f_results')->select('id', 'title');
    }


    


    public function priority()
    {

        return $this->hasOne(Priority::class, 'id', 'priority')->select('id', 'title');
    }
    public function status()
    {

        return $this->hasOne(Status::class, 'id', 'status')->select('id', 'title');
    }

    public function package()
    {

        return $this->hasOne(Package::class, 'id', 'package')->select('id', 'title');
    }

    public function cancel_reason()
    {

        return $this->hasOne(CancelReason::class, 'id', 'cancel_reason')->select('id', 'title');
    }

    public function user()
    {

        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name', 'team');
    }
    public function section()
    {

        return $this->hasOne(Sections::class, 'id', 'sections')->select('id', 'title');
    }
}
