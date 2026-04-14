<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RekapGajiPeriodeExport;

class RekapGajiPeriodePdfController extends Controller
{
    public function export(Request $request)
    {
        $rekapIds = array_filter((array) $request->query('rekap_ids', []));

        $pdfExport = new RekapGajiPeriodeExport($rekapIds);

        // sesuaikan dengan isi class kamu
        return $pdfExport->download('rekap-gaji-periode.pdf');
        // atau:
        // return $pdfExport->stream();
        // atau:
        // return $pdfExport->render();
    }
}