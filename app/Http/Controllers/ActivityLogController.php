<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ActivityLogsExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ActivityLogsExport, 'data-logs.xlsx');
    }

    public function exportPdf()
    {
        $logs = ActivityLog::latest()->latest()->get();
        $pdf = Pdf::loadView('activity_logs.pdf', compact('logs'));
        return $pdf->download('data-logs.pdf');
    }

    public function index()
    {
        $logs = ActivityLog::latest()->paginate(10);
        return view('activity_logs.index', compact('logs'));
    }
}
