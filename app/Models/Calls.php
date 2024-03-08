<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Carbon;

class Calls extends Model
{

    protected $table = 'calls';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'first_name', 'last_name', 'phone_number', 'whatsapp', 'email',  'priority', 'note', 'file_name', 'sections', 'follow_up_date', 'follow_up_notes', 'status', 'package', 'last_contact', 'age', 'gpa', 'last_status_date', 'last_status_notes', 'results', 'cancel_reason', 'feedbacks', 'user_id', 'memo', 'ag', 'f_results', 'referred_by', 'first_contact', 'marital_status', 'want_to_study', 'assigned_to', 'assigned_date', 'applying_for', 'confirmed_gpa', 'immigration_filling', 'method_filling', 'goal', 'nationality', 'package_explain', 'agreement_sent', 'agree_date_sent', 'degree', 'field_study', 'call_schedule_date', 'call_schedule_time', 'eng_test', 'eng_test_score', 'next_step', 'payment_method', 'agreed_to_pay', 'agreed_to_signed', 'agreement_signed_date', 'cancel_note', 'cancel_date', 'first_call_notes', 'case_type', 'sort', 'p_sort'
    ];


    public function versions()
    {
        return $this->hasMany(CallHistory::class, 'call_id', 'id');
    }


    public function call_schedule()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups', 'call_schedule')->orderBy('id', 'DESC')->select('id', 'groups', 'call_id');
    }
    public function my_step()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups', 'my_step')->orderBy('id', 'DESC')->select('id', 'groups', 'call_id');
    }

    public function con_gpa()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups', 'con_gpa')->orderBy('id', 'DESC')->select('id', 'groups', 'call_id');
    }
    public function follow_up()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->where('groups', 'follow_up')->orderBy('id', 'DESC')->select('id', 'groups', 'call_id');
    }







    public function steps()
    {
        return $this->hasOne(ExtraGroups::class, 'call_id', 'id')->where('groups', 'my_step')->orderBy('id', 'DESC')->select('id', 'groups', 'call_id');
    }


    public function extra()
    {
        return $this->hasMany(ExtraGroups::class, 'call_id', 'id')->orderBy('id', 'ASC')->select('id', 'groups', 'call_id');
    }

    public function history()
    {
        return $this->hasMany(CallsExtra::class, 'call_id', 'id')->orderBy('id', 'DESC')->select('id', 'call_id', 'field', 'value', 'created_at', 'user_id');
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

    public function p_sort()
    {

        return $this->hasOne(Priority::class, 'id', 'p_sort')->select('id', 'title');
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
    public function cancelReason()
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

    public function assignedTo()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to')->select('id', 'first_name', 'last_name', 'team');
    }

    public function assigned_to()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to')->select('id', 'first_name', 'last_name', 'team');
    }
    public function goal()
    {
        return $this->hasOne(Goal::class, 'id', 'goal')->select('id', 'title');
    }

    public function all_packages()
    {
        return $this->belongsTo(Package::class);
    }

    public function userBelong()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }


    private function PackageParcent($team, $start_date, $end_date)
    {
        $packageCounts = Package::withCount(['calls' => function ($query) use ($start_date, $end_date, $team) {
            // Another subquery to count calls within the date range
            $query->whereBetween('created_at', [$start_date, $end_date])
                ->whereHas('userBelong', function ($q) use ($team) {
                    $q->where('team', $team);
                });
        }])->get();

        $packageTotal = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])->whereNotNull('package')->where('package', '<>', 0)->count();

        if ($packageTotal == 0) return [];
        $packagesPercentage = $packageCounts->map(function ($status) use ($packageTotal) {

            $per = ($status->calls_count / $packageTotal) * 100;


            return [
                'id' => $status->id,
                'p' => number_format($per, 2),
                'title' => $status->title
            ];
        });

        return $packagesPercentage;
    }


    private function MaritalStatus($team, $start_date, $end_date)
    {

        $statusCounts = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])
            ->whereIn('marital_status', [1, 2, 3])
            ->selectRaw('marital_status, COUNT(*) as count')
            ->groupBy('marital_status')
            ->pluck('count', 'marital_status');

        $marriedCount = $statusCounts->get(1, 0);
        $unmarriedCount = $statusCounts->get(2, 0);
        $applyingCount = $statusCounts->get(3, 0);


        $maritalTotal = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])->whereNotNull('marital_status')->where('marital_status', '<>', 0)->count();

        if ($maritalTotal == 0) return [];
        $marriedPercentage = ($marriedCount / $maritalTotal) * 100;
        $unmarriedPercentage = ($unmarriedCount / $maritalTotal) * 100;
        $applyingPercentage = ($applyingCount / $maritalTotal) * 100;

        return array(
            number_format($marriedPercentage, 2),
            number_format($unmarriedPercentage, 2),
            number_format($applyingPercentage, 2)
        );
    }

    private function CaseType($team, $start_date, $end_date)
    {
        $counts = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])
            ->whereIn('case_type', [1, 2])
            ->selectRaw('case_type, COUNT(*) as count')
            ->groupBy('case_type')
            ->pluck('count', 'case_type');

        $f1Count = $counts->get(1, 0);
        $f2Count = $counts->get(2, 0);


        $caseTypeTotal = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])->whereNotNull('case_type')->where('case_type', '<>', 0)->count();
        if ($caseTypeTotal == 0) return [];
        $f1Percentage = ($f1Count / $caseTypeTotal) * 100;
        $f2Percentage = ($f2Count / $caseTypeTotal) * 100;

        $return['percent'] = array(
            number_format($f1Percentage, 2),
            number_format($f2Percentage, 2)
        );
        $return['count'] = array(
            $f1Count,
            $f2Count
        );

        return $return;
    }

    private function Status($team, $start_date, $end_date)
    {
        $statusCounts = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])
            ->whereIn('status', [1, 2, 3])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $hotCount = $statusCounts->get(1, 0);
        $warmCount = $statusCounts->get(2, 0);
        $coldCount = $statusCounts->get(3, 0);



        $statusTotal = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])->whereIn('status', [1, 2, 3])->count();
        if ($statusTotal == 0) return [];
        $hotPercentage = ($hotCount / $statusTotal) * 100;
        $warmPercentage = ($warmCount / $statusTotal) * 100;
        $coldPercentage = ($coldCount / $statusTotal) * 100;

        return array(
            number_format($hotPercentage, 2),
            number_format($warmPercentage, 2),
            number_format($coldPercentage, 2),
        );
    }


    private function AgreementSent($team, $start_date, $end_date)
    {
        $agCounts = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])
            ->whereIn('ag', [0, 1])
            ->selectRaw('ag, COUNT(*) as count')
            ->groupBy('ag')
            ->pluck('count', 'ag');

        $agNoCount = $agCounts->get(0, 0);
        $agYesCount = $agCounts->get(1, 0);


        $totalUsers = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])->count();

        if ($totalUsers == 0) return [];

        $agNoPercentage = ($agNoCount / $totalUsers) * 100;
        $agYesPercentage = ($agYesCount / $totalUsers) * 100;



        return array(
            number_format($agYesPercentage, 2),
            number_format($agNoPercentage, 2)

        );
    }

    private function agSigned($team, $start_date, $end_date)
    {
        $agreedToSignedCounts = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])
            ->whereIn('agreed_to_signed', [0, 1])
            ->selectRaw('agreed_to_signed, COUNT(*) as count')
            ->groupBy('agreed_to_signed')
            ->pluck('count', 'agreed_to_signed');

        $agToSigned = $agreedToSignedCounts->get(1, 0);
        $notAgreeToSigned = $agreedToSignedCounts->get(0, 0);


        $agSignedtotal = self::whereHas('userBelong', function ($query) use ($team) {
            // Filter by user type 1 or 2
            $query->where('team', $team);
        })->whereBetween('created_at', [$start_date, $end_date])->whereNotNull('agreed_to_signed')->count();

        if ($agSignedtotal == 0) return [];


        $agSignedPercentage = ($agToSigned / $agSignedtotal) * 100;
        $agNoSignedPercentage = ($notAgreeToSigned / $agSignedtotal) * 100;



        $return['agsigned'] = array(
            number_format($agSignedPercentage, 2),
            number_format($agNoSignedPercentage, 2),
        );
    }




    public  function getTeamPercentage($team, $s_date, $e_date)
    {
        // $start_date = $s_date;
        // $end_date = $e_date;
        // $totalUsers = self::whereHas('userBelong', function ($query) use ($team) {
        //     // Filter by user type 1 or 2
        //     $query->where('team', $team);
        // })->whereBetween('created_at', [$start_date, $end_date])->count();

        // if ($totalUsers == 0) return [];

        $return['packages'] = $this->PackageParcent($team, $s_date, $e_date);
        $return['agsigned'] = $this->agSigned($team, $s_date, $e_date);
        $return['marital_status'] = $this->MaritalStatus($team, $s_date, $e_date);
        $return['case_type'] = $this->CaseType($team, $s_date, $e_date);
        $return['status'] = $this->Status($team, $s_date, $e_date);
        $return['agreement_sent'] = $this->AgreementSent($team, $s_date, $e_date);




        return $return;
    }






    public static function getPercentage($s_date, $e_date)
    {

        $return['packages'] = [];
        $return['agsigned'] = [];
        $return['marital_status'] = [];
        $return['case_type'] = [];
        $return['status'] = [];
        $return['agreement_sent'] = [];
        $start_date = $s_date;
        $end_date = $e_date;
        $totalUsers = self::whereBetween('created_at', [$start_date, $end_date])->count();

        if ($totalUsers == 0)  return $return;





        $packageCounts = Package::withCount(['calls' => function ($query) use ($start_date, $end_date) {
            // Another subquery to count calls within the date range
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }])->get();

        //  return  $packageCounts;
        //   return $return = array($start_date, $end_date);




        //marital_status
        $marriedCount = self::whereBetween('created_at', [$start_date, $end_date])->where('marital_status', 1)->count();
        $unmarriedCount = self::whereBetween('created_at', [$start_date, $end_date])->where('marital_status', 2)->count();
        $applyingCount = self::whereBetween('created_at', [$start_date, $end_date])->where('marital_status', 3)->count();










        $maritalTotal = self::whereBetween('created_at', [$start_date, $end_date])->whereNotNull('marital_status')->where('marital_status', '<>', 0)->count();

        $marriedPercentage = ($marriedCount / $maritalTotal) * 100;
        $unmarriedPercentage = ($unmarriedCount / $maritalTotal) * 100;
        $applyingPercentage = ($applyingCount / $maritalTotal) * 100;

        $return['marital_status'] = array(
            number_format($marriedPercentage, 2),
            number_format($unmarriedPercentage, 2),
            number_format($applyingPercentage, 2)
        );




        //case_type
        $f1Count = self::whereBetween('created_at', [$start_date, $end_date])->where('case_type', 1)->count();
        $f2Count = self::whereBetween('created_at', [$start_date, $end_date])->where('case_type', 2)->count();

        $caseTypeTotal = self::whereBetween('created_at', [$start_date, $end_date])->whereNotNull('case_type')->where('case_type', '<>', 0)->count();

        $f1Percentage = ($f1Count / $caseTypeTotal) * 100;
        $f2Percentage = ($f2Count / $caseTypeTotal) * 100;

        $return['case_type']['percent'] = array(
            number_format($f1Percentage, 2),
            number_format($f2Percentage, 2)
        );
        $return['case_type']['count'] = array(
            $f1Count,
            $f2Count
        );


        $hotCount = self::whereBetween('created_at', [$start_date, $end_date])->where('status', 1)->count();
        $warmCount = self::whereBetween('created_at', [$start_date, $end_date])->where('status', 2)->count();
        $coldCount = self::whereBetween('created_at', [$start_date, $end_date])->where('status', 3)->count();

        $statusTotal = self::whereBetween('created_at', [$start_date, $end_date])->whereIn('status', [1, 2, 3])->count();

        $hotPercentage = ($hotCount / $statusTotal) * 100;
        $warmPercentage = ($warmCount / $statusTotal) * 100;
        $coldPercentage = ($coldCount / $statusTotal) * 100;

        $return['status'] = array(
            number_format($hotPercentage, 2),
            number_format($warmPercentage, 2),
            number_format($coldPercentage, 2),
        );





        $packagetotal = self::whereBetween('created_at', [$start_date, $end_date])->whereNotNull('package')->where('package', '<>', 0)->count();


        $packagesPercentage = $packageCounts->map(function ($status) use ($packagetotal) {

            $per = ($status->calls_count / $packagetotal) * 100;


            return [
                'id' => $status->id,
                'p' => number_format($per, 2),
                'title' => $status->title
            ];
        });

        $return['packages'] = $packagesPercentage;





        $agNoCount = self::whereBetween('created_at', [$start_date, $end_date])->where('ag', 0)->count();
        $agYesCount = self::whereBetween('created_at', [$start_date, $end_date])->where('ag', 1)->count();


        $agNoPercentage = ($agNoCount / $totalUsers) * 100;
        $agYesPercentage = ($agYesCount / $totalUsers) * 100;



        $return['agreement_sent'] = array(
            number_format($agYesPercentage, 2),
            number_format($agNoPercentage, 2)

        );

        //Agreement Signed 

        $agToSigned = self::whereBetween('created_at', [$start_date, $end_date])->where('agreed_to_signed', 1)->count();
        $NotAgreeToSigned = self::whereBetween('created_at', [$start_date, $end_date])->where('agreed_to_signed', 0)->count();

        $agSignedtotal = self::whereBetween('created_at', [$start_date, $end_date])->whereNotNull('agreed_to_signed')->count();




        $agSignedPercentage = ($agToSigned / $agSignedtotal) * 100;
        $agNoSignedPercentage = ($NotAgreeToSigned / $agSignedtotal) * 100;



        $return['agsigned'] = array(
            number_format($agSignedPercentage, 2),
            number_format($agNoSignedPercentage, 2),
        );


        return $return;
    }

    // public static function getCaseTypePercentage()
    // {
    //     $totalUsers = self::count();

    //     $f1Count = self::where('case_type', 1)->count();
    //     $f2Count = self::where('case_type', 2)->count();


    //     $f1Percentage = ($f1Count / $totalUsers) * 100;
    //     $f2Percentage = ($f2Count / $totalUsers) * 100;

    //     return [
    //         'f1' => $f1Percentage,
    //         'f2' => $f2Percentage
    //     ];
    // }
}
