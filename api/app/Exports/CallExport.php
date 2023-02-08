<?php
namespace App\Exports;
use App\Models\Calls;
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

    protected $year;
    
    public function __construct(int $year)
    {
        $this->year = $year;
    }
 
 
    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
       $sections = Sections::all();
        foreach($sections as $sec){
            $sheets[] = new InvoicesPerMonthSheet($sec->id, $sec->title);
        }
        return $sheets;
    }
}
