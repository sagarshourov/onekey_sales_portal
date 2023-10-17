<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\Calls;

use App\Models\Package;
use App\Models\Status;
use App\Models\Sections;

class SingleSheetImport implements ToCollection
{



    private $user_id;
    private $file_name;
    private $result;

    public function __construct(int $user_id, string $file_name, int $result)
    {
        $this->user_id = $user_id;

        $this->file_name = $file_name;
        $this->result = $result;
    }
    private $sections = null;

    private function check($title)
    {

        // echo $title;

        // echo '<br/>';

        if (preg_match('(Installment|Instalment|Agreement|Signed|Did|Done|Seminar|Promotions|Promotion)', $title)) {
            // echo $title;
            // echo '<br/>';
            $pkg =  Sections::firstOrCreate([
                'title' => $title
            ]);

            //$this->sections=$pkg->id;

            return $pkg->id;
        } else  if (preg_match('(Non Section)', $title)) {
            $this->sections = null;
            return null;
        }
        $this->sections = null;
        return $this->sections;
    }


    public function collection(Collection $rows)
    {
        //         $package = Package::get()->pluck('title')->toArray();
        // print_r($package);
        //echo  $this->file_name;
        foreach ($rows as $row) {
            if ($row[0] == 'First Name') {
                continue;
            }

            if (!empty($row[1])) {
                // print_r($row[0]);
                // //   // print_r($row);
                // echo '<br/>';

                // echo 'sections : ' . $this->sections;
                // echo '<br/>';
                $in['first_name'] = $row[0];
                $in['last_name'] = $row[1];
                $in['phone_number'] = $row[2];

                $in['whatsapp'] = $row[2];
                $in['email'] = $row[3];

                $in['last_status_notes'] = $row[4];

                $in['status'] = Status::firstOrCreate([
                    'title' => $row[5]
                ])->id;; //db
                $in['ag'] = $row[6] == 'TRUE' ? 1 : 0; //db
                if ($this->result !== 1) {
                    $in['package'] =      $row[7] !== null ? Package::firstOrCreate([
                        'title' => $row[7]
                    ])->id : 1;
                } else {
                    $in['package'] = null;
                }
                //db
                $in['age'] = $row[9]; //db
                $in['follow_up_notes'] = $row[10];
                $in['sections'] = $this->sections;
                $in['user_id'] = $this->user_id;
                $in['file_name'] = $this->file_name;
                $in['results'] = $this->result;
                $in['f_results'] = $this->result;
                $in['assigned_to'] = $this->user_id;
                Calls::create($in);
                // echo '<br/>';
                //echo '______END///////////////________';
            } else if (!empty($row[0])) {
                // print_r($row[0]);
                // echo '<br/>';
                // print_r($row[1]);
                $this->sections = $this->check($row[0]);
            }
        }
        // echo '______END///////////////________';
        // echo '<br/>';
    }
}
