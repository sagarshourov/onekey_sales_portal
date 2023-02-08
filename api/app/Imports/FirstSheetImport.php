<?php 
namespace App\Imports;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\Calls;

use App\Models\Package;
use App\Models\Status;
use App\Models\Sections;

class FirstSheetImport implements ToCollection
{
    private $sections=null;

    private function check($title){
      $pkg =  Sections::firstOrCreate([
            'title' => $title
        ]);

        //$this->sections=$pkg->id;

        return $pkg->id;
    }


    public function collection(Collection $rows)
    {
//         $package = Package::get()->pluck('title')->toArray();
// print_r($package);

        foreach ($rows as $row) 
        {
            if($row[0]=='First Name'){
              continue;
            }

            if(!empty($row[1])){
                print_r($row[0]);
              // print_r($row);
               echo '<br/>';
               
               echo $this->sections;
            $in['first_name'] = $row[0];
            $in['last_name']=$row[1];
            $in['phone_number']=$row[2];
            $in['email']=$row[3];
             
            $in['last_status_notes']=$row[4];

            $in['status']=Status::firstOrCreate([
                'title' => $row[5]
            ])->id;; //db
            $in['ag']=$row[6]; //db

            $in['package'] = Package::firstOrCreate([
                'title' => $row[7]
            ])->id; //db
            $in['age']=$row[9]; //db
            $in['follow_up_notes']=$row[10];
            $in['sections']=$this->sections;
            Calls::create($in);            
            echo '<br/>';
                //echo '______END///////////////________';
            }else if(!empty($row[0])){
                print_r($row[0]);
                echo '<br/>';
                print_r($row[1]);
                $this->sections=$this->check($row[0]);
            }
        } 
        echo '______END///////////////________';
        echo '<br/>';
    }
    
}
