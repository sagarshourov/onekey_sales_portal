<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    //
    public function index($team, $sdate, $edate)
    {
        // 1. Marital Status -This shows the number and Percentage of each category
        //2. Case Type- This shows the number and Percentage of each category
        //3. Status- This shows the number and Percentage of each category
        //4. Packages - This shows the number and Percentage of each category
        //5. Agreement Sent - This shows the number and Percentage of each category
        //6. Agreement Signed - This shows the number and Percentage of each category
        //7. Sales Report- This would show the Sales projections. For this, we must assign a dollar value to Packages in the backend. Its should be defined as:


        // Team filter : select user table check user belongs to from IR or tr  
        // 2md logic select calls check assigned_to (user id) then check user table where is this user belongs IR or TR  

        if ($team == 1) {

            $call = new Calls();


            return $this->sendResponse($call->getTeamPercentage(1, $sdate, $edate), 'Calls Retrieve successfully.');
        } else if ($team == 2) {
            $call = new Calls();


            return $this->sendResponse($call->getTeamPercentage(2, $sdate, $edate), 'Calls Retrieve successfully.');
        } else {
            return $this->sendResponse(Calls::getPercentage($sdate, $edate), 'Calls Retrieve successfully.');
        }
    }
}
