<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Exports\ActivityLogsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas dengan pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $logs = ActivityLog::latest()->paginate(10);

        return view('activity_logs.index', compact('logs'));
    }

    /**
     * Mengekspor seluruh log aktivitas ke dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel()
    {
        return Excel::download(new ActivityLogsExport, 'data-logs.xlsx');
    }

    /**
     * Mengekspor seluruh log aktivitas ke dalam format PDF.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPdf()
    {
        $logs = ActivityLog::latest()->get();

        $pdf = Pdf::loadView('activity_logs.pdf', compact('logs'));

        return $pdf->download('data-logs.pdf');
    }
}
