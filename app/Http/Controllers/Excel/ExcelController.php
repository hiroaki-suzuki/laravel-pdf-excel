<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use function date_diff;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use function resource_path;

class ExcelController extends Controller
{
    private const FIRST_ROW = 5;

    private const FIRST_COL = 2;

    public function create()
    {
        $data = [
            ['123-45-6778', '商品１', '01', '鈴木', '良'],
            ['123-45-7777', '商品２', '01', '清水', '良'],
            ['123-45-6666', '商品３', '01', '田中', '否'],
        ];

        $reader = new XlsxReader();
        $spreadsheet = $reader->load(resource_path('list/template.xlsx'));

        $coverSheet = $spreadsheet->getSheetByName('表紙');
        $coverSheet->setCellValue('G17', date('yyyy年MM月dd日'));

        $listSheet = $spreadsheet->getSheetByName('リスト');
        $listSheet->setCellValue('C2', '伝票');

        foreach ($data as $i => $record) {
            $rowNum = (int)$i + self::FIRST_ROW;
            foreach ($record as $j => $value) {
                $colNum = (int)$j + self::FIRST_COL;
                $listSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
                $cell = $listSheet->getCellByColumnAndRow($colNum, $rowNum);
                $cell->getStyle()->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ], 'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ], 'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ], 'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ]
                ]);
            }
        }

        $writer = new XlsxWriter($spreadsheet);;
        $writer->save('myexcel.xlsx');

        return response()->download('myexcel.xlsx')->deleteFileAfterSend(true);
    }
}