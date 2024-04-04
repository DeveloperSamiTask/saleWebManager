<?php

namespace App\Exports;

use App\Models\DetCart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DetCartExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Price',
            'Shift',
            'Name',
            'Device',
            'Code',
            'Purchase',
            'Income',
            'Sure',
            'Status',
            'Used',
        ];
    }
}
