<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;


class ReporteStockExports implements FromView
{

    private $stocks;
 
    public function __construct($stocks)
    {
        $this->stocks = $stocks;
    }

    public function view(): View
    {
        return view('exports.stock', [
            'stocks' => $this->stocks
        ]);
    }
}