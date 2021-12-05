<?php

namespace App\Http\Controllers\API;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheets implements WithMultipleSheets
{
    use Exportable;

    protected $municipality;
    
    public function __construct($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        for ($i = 0; $i < COUNT($this->municipality); $i++) {
            $sheets[] = new ExportScholars($this->municipality[$i]);
        }

        return $sheets;
    }
}