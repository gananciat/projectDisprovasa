<?php

namespace App\Exports;

use App\Models\Order;
use App\Traits\HelperReportStyle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ProductProviderExport implements FromArray, ShouldAutoSize, WithEvents, WithCustomStartCell, WithDrawings, WithTitle
{
	use HelperReportStyle;
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $description;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
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
            	$event->sheet->setCellValue('D3', 'REPORTE PRECIO PRODUCTOS POR PROVEEDOR', 'UTF-8');

                $styleHeaderArray = $this->getStyleToTitle();
                $event->sheet->getStyle('A1:J6')->applyFromArray($styleHeaderArray);//title header
                
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('logo');
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
