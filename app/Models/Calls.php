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
        'first_name', 'last_name', 'phone_number', 'whatsapp', 'email',  'priority', 'note', 'file_name', 'sections', 'follow_up_date', 'follow_up_notes', 'status', 'package', 'last_contact', 'age', 'gpa', 'last_status_date', 'last_status_notes', 'results', 'cancel_reason', 'feedbacks', 'user_id', 'memo', 'ag', 'f_results', 'referred_by', 'first_contact', 'marital_status', 'want_to_study', 'assigned_to', 'assigned_date', 'applying_for', 'confirmed_gpa', 'immigration_filling', 'method_filling', 'goal', 'nationality', 'package_explain', 'agreement_sent', 'agree_date_sent', 'degree', 'field_study', 'call_schedule_date', 'call_schedule_time', 'eng_test', 'eng_test_score', 'next_step', 'payment_method', 'agreed_to_pay', 'agreed_to_signed', 'agreement_signed_date', 'cancel_note', 'cancel_date', 'first_call_notes', 'case_type' , 'sort'
    ];


    public function versions()
    {
        return $this->hasMany(CallHistory::class, 'call_id', 'id');
    }


    public function call_schedule()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups','call_schedule')->orderBy('id','DESC')->select('id', 'groups', 'call_id');
    }
    public function my_step()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups','my_step')->orderBy('id','DESC')->select('id', 'groups', 'call_id');
    }

    public function con_gpa()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups','con_gpa')->orderBy('id','DESC')->select('id', 'groups', 'call_id');
    }
    public function follow_up()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups','follow_up')->orderBy('id','DESC')->select('id', 'groups', 'call_id');
    }

    

    



    public function steps()
    {
        return $this->hasOne(ExtraGroups::class, 'call_id', 'id')->where('groups','my_step')->orderBy('id','DESC')->select('id', 'groups', 'call_id');
    }


    public function extra()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->orderBy('id','ASC')->select('id', 'groups', 'call_id');
    }

    public function history()
    {
        return $this->hasMany(CallsExtra::class, 'call_id', 'id')->orderBy('id','DESC')->select('id', 'call_id', 'field', 'value', 'created_at', 'user_id');
    }



    public function results()
    {
        return $this->hasOne(Results::class, 'id', 'results')->select('id', 'title');
    }

    public function follow_up_call_results()
    {
        return $this->hasOne(Results::class, 'id', 'f_results')->select('id', 'title');
    }





    public function priorities()
    {

        return $this->hasOne(Priority::class, 'id', 'priority')->select('id', 'title');
    }
    public function statu()
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

    public function marital_status()
    {

        return $this->hasOne(MaritalStatus::class, 'id', 'marital_status')->select('id', 'title');
    }

    public function applying_for()
    {

        return $this->hasOne(ApplyingFor::class, 'id', 'applying_for')->select('id', 'title');
    }
    public function want_to_study()
    {
        return $this->hasOne(WantToStudy::class, 'id', 'want_to_study')->select('id', 'title');
    }

    public function assigned_to()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to')->select('id', 'first_name', 'last_name', 'team');
    }
    public function goal()
    {
        return $this->hasOne(Goal::class, 'id', 'goal')->select('id', 'title');
    }
}