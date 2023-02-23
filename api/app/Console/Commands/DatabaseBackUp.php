<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;


use Mail;



// class DatabaseBackUp extends Command
// {
//     /**
//      * The name and signature of the console command.
//      *
//      * @var string
//      */
//     protected $signature = 'command:name';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Command description';

//     /**
//      * Execute the console command.
//      *
//      * @return int
//      */
//     public function handle()
//     {
//         return Command::SUCCESS;
//     }
// }

class DatabaseBackUp extends Command

{

    /**

     * The name and signature of the console command.

     *

     * @var string

     */

    protected $signature = 'database:backup';



    /**

     * The console command description.

     *

     * @var string

     */

    protected $description = 'Command description';



    /**

     * Create a new command instance.

     *

     * @return void

     */

    public function __construct()

    {

        parent::__construct();
    }


    /**

     * Execute the console command.

     *

     * @return int

     */

    public function handle()

    {

        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";


        $file_path = storage_path() . "/app/backup/" . $filename;

        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . $file_path;



        $returnVar = NULL;

        $output  = NULL;



        exec($command, $output, $returnVar);
    }
}
