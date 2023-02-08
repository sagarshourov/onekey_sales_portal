<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Calls;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
class InvoicesPerMonthSheet implements  WithTitle,FromView,WithEvents
{
    private $title;
    private $id;
    protected  $selects;
    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title  = $title;

        $status=['active','pending','disabled'];
        $departments=['Account','Admin','Ict','Sales'];
        $roles=['role1','role2','role3','role4'];
        $selects=[  //selects should have column_name and options
            ['columns_name'=>'D','options'=>$departments],
            ['columns_name'=>'E','options'=>$status],
            ['columns_name'=>'F','options'=>$roles],
        ];
        $this->selects=$roles;


    }

    public function view(): View
    {
        return view('invoices', [
            'invoices' => Calls::with('goal')->where('sections', $this->id)->get()
        ]);
    }
  

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }


    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
                $validation = $event->sheet->getCell("C3")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST );
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1(sprintf('"%s"',implode(',',$this->selects)));

            },
        ];
    }




}
