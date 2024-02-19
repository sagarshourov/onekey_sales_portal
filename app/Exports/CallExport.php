<?php

namespace App\Exports;

use App\Models\Calls;
use App\Models\Results;
use App\Models\Sections;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CallExport implements WithMultipleSheets
{



    use Exportable;

    protected $result;
    protected $title;
    protected $user_id;
    protected $off;

    public function __construct(int $result, string $title, int $user_id, $off)
    {
        $this->result = $result;

        $this->title = $title;
        $this->user_id = $user_id;
        $this->off = $off;
    }


    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sections = Results::all();
        // $sheets[] = new InvoicesPerMonthSheet(NULL, 'Call');


        if ($this->result == 0) {
            foreach ($sections as $sec) {
                $sheets[] = new InvoicesPerMonthSheet($sec->id, $sec->title, $this->user_id, $this->off);
            }
        } else {
            $sheets[] = new InvoicesPerMonthSheet($this->result, $this->title, $this->user_id, $this->off);
        }



        return $sheets;
    }
}
