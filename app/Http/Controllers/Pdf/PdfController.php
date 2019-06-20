<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use mikehaertl\wkhtmlto\Pdf;
use TCPDF;

class PdfController extends Controller
{

    public function create()
    {
        $pdf = new Pdf(['enable-javascript', 'debug-javascript', 'no-stop-slow-scripts', 'javascript-delay' => 1000]);
        $pdf->addPage('http://192.168.10.10/report');

        $fileName = storage_path('app/report.pdf');
        if (!$pdf->saveAs($fileName)) {
            logger($pdf->getError());
        }

        return response()->file($fileName)->deleteFileAfterSend(true);
    }
}