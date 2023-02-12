<?php
namespace App\Imports;
use App\Models\Calls;
use App\Models\Sections;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class CallImport implements WithMultipleSheets,SkipsUnknownSheets
{ 


    private $user_id;
    private $file_name;

    public function __construct( int $user_id, string $file_name)
    {
        $this->user_id = $user_id;
   
        $this->file_name = $file_name;
    }
    /**
     * @return  
     */
    public function sheets(): array
    {


        Calls::where('file_name', $this->file_name)->delete();        
        return [
            'Call' => new FirstSheetImport($this->user_id,$this->file_name),
            'Cancel' => new SingleSheetImport($this->user_id,$this->file_name,1),
            'Client' => new SingleSheetImport($this->user_id,$this->file_name,2)
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
