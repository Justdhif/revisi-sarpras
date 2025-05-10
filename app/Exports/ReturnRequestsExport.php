<?php

namespace App\Exports;

use App\Models\ReturnRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturnRequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ReturnRequest::with('borrowRequest.user')->get()->map(function ($return) {
            return [
                'ID' => $return->id,
                'Peminjam' => $return->borrowRequest->user->username ?? '-',
                'Status' => ucfirst($return->status),
                'Catatan' => $return->notes,
                'Tanggal Dibuat' => $return->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Peminjam', 'Status', 'Catatan', 'Tanggal Dibuat'];
    }
}
