<?php

//use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

//require __DIR__ . '/../Header.php';

require_once __DIR__ . '/../../src/Bootstrap.php';

//$helper = new Sample();

//$helper->log('Load from Xls template');
$reader = IOFactory::createReader('Xls');
$spreadsheet = $reader->load(__DIR__ . '/../templates/CalendarTemplate.xls');

//$helper->log('Add new data to the template');
$data = [['title' => 'Excel for dummies',
        'price' => 17.99,
        'quantity' => 2,
    ],
    ['title' => 'PHP for dummies',
        'price' => 15.99,
        'quantity' => 1,
    ],
    ['title' => 'Inside OOP',
        'price' => 12.95,
        'quantity' => 1,
    ],
];

$spreadsheet->getActiveSheet()->setCellValue('D1', Date::PHPToExcel(time()));

$row=4;

$spreadsheet->getActiveSheet()->setCellValue('A' . $row, $r + 1)
        ->setCellValue('B' . $row, 'title')
        ->setCellValue('C' . $row, 'price')
        ->setCellValue('D' . $row, 'quantity')
        ->setCellValue('E' . $row, 'quantity')
        ->setCellValue('F' . $row, 'quantity')
        ->setCellValue('G' . $row, 'quantity')
        ->setCellValue('H' . $row, 'Algo');


/*
$baseRow = 5;
foreach ($data as $r => $dataRow) {
    $row = $baseRow + $r;
    $spreadsheet->getActiveSheet()->insertNewRowBefore($row, 1);

    $spreadsheet->getActiveSheet()->setCellValue('A' . $row, $r + 1)
        ->setCellValue('B' . $row, $dataRow['title'])
        ->setCellValue('C' . $row, $dataRow['price'])
        ->setCellValue('D' . $row, $dataRow['quantity'])
        ->setCellValue('E' . $row, '=C' . $row . '*D' . $row);
}
$spreadsheet->getActiveSheet()->removeRow($baseRow - 1, 1);
*/
// Save
//$helper->write($spreadsheet, __FILE__);


// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
