<?php
namespace App\Imports;
use App\Models\Calls;
use App\Models\Sections;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class CallImport implements WithMultipleSheets,SkipsUnknownSheets
{ 
    /**
     * @return  
     */
    public function sheets(): array
    {
        return [
            'Call' => new FirstSheetImport()
        ];
        
        // $sections = Sections::all();
        // $sheet = [];
        
        //         foreach($sections as $sec){
        //            $sheet[] = new FirstSheetImport();
        //         }
        
        // return $sheet;

    }
    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        echo("Sheet {$sheetName} was skipped");
    }
}
