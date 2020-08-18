<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;


class ActivoExports implements FromView
{

    private $activos;
 
    public function __construct($activos)
    {
        $this->activos = $activos;
    }

    public function view(): View
    {
        return view('exports.activo', [
            'activos' => $this->activos
        ]);
    }
}