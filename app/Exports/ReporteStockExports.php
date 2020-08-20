<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class ReporteStockExports implements FromView,ShouldAutoSize
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