<?php
namespace App\Exports;

use App\Models\Calls;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class UsersExport implements FromView
{
    public function view(): View
    {
        return view('invoices', [
            'invoices' => Calls::all()
        ]);
    }
}