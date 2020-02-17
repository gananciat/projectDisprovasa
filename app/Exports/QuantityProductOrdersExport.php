<?php

namespace App\Exports;

use App\Models\Order;
use App\Traits\HelperReportStyle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class QuantityProductOrdersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCustomStartCell, WithDrawings, WithTitle
{
	use HelperReportStyle;
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $description;

    public function __construct($data, $description)
    {
        $this->data = $data;
        $this->description = $description;
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Cantidad pedida',
            'Cantidad entregada',
        ];
    }

    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'B8';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
            	$event->sheet->mergeCells('A1:C3');
            	$event->sheet->mergeCells('D1:I2');
            	$event->sheet->mergeCells('D3:I3');
            	$event->sheet->setCellValue('D1', 'PROALSA');
            	$event->sheet->setCellValue('D3', mb_strtoupper($this->description, 'UTF-8'));
				
            	$styleArray = $this->getStyleToHead();
            	$styleHeaderArray = $this->getStyleToTitle(); 

                $cellRange = 'B8:D8'; // All headers
                $event->sheet->setAutoFilter('B8:D8');
                $event->sheet->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getStyle('A1:J6')->applyFromArray($styleHeaderArray);//title header
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('logo_institucion');
        $drawing->setPath(public_path('img/logo_empty.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function title(): string
    {
        return 'productos pedidos';
    }
}
