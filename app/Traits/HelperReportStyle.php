<?php 

namespace App\Traits;

use App\myClass\Reporte;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;

trait HelperReportStyle
{
    protected function getStyleToHead()
    {
        return  [
                    //Set border Style
                    'borders' => [ 
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            //'color' => ['argb' => 'EB2B04'],
                        ],

                    ],

                    //Set font style
                    'font' => [
                        'name'      =>  'Calibri',
                        'size'      =>  15,
                        'bold'      =>  true,
                        'color' => ['argb' => 'EB2B02'],
                    ],

                    //Set background style
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'a1dae7',
                        ] 
                    ] 
             ]; 
    }

    
    protected function getStyleToTitle()
    {
        return  [
                    //Set border Style
                    'borders' => [ 
                        'outline' => [
                            //'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            //'color' => ['argb' => 'EB2B04'],
                        ],

                    ],

                    //Set font style
                    'font' => [
                        'name'      =>  'Calibri',
                        'size'      =>  15,
                        'bold'      =>  true,
                        'color' => ['argb' => '354D73'],
                    ],

                    //Set background style
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'F6F6F6',
                        ] 
                    ] 
             ]; 
    }

    protected function alignmentCenter()
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ];
    }

    protected function alignmentRight()
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            ]
        ];
    }

    protected function alignmentLeft()
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ];
    }

    protected function borderSum($cant = 0)
    {
        $color = $cant < 0 ? 'EB2B02' : '0E294B';

        return [
            'font' => [
                'bold' => true,
                'size'      =>  15,
                'color' => ['argb' => $color]
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
            ]
        ];
    }

    protected function getSizeLetter($size)
    {
        return [
            'font' => [
                'bold' => true,
                'size'      =>  $size
            ]
        ];
    }
}