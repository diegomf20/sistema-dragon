<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataExports implements FromView,ShouldAutoSize
{

    private $activos;
    private $vista;
    
    public function __construct($activos,$vista)
    {
        $this->activos = $activos;
        $this->vista = $vista;
    }

    public function view(): View
    {
        return view($this->vista, [
            'datos' => $this->activos
        ]);
    }
}