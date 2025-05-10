<?php

namespace App\Exports;

use App\Models\BorrowRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowRequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BorrowRequest::with('user', 'approver')->get()->map(function ($borrow) {
            return [
                'ID' => $borrow->id,
                'Peminjam' => $borrow->user->username ?? '-',
                'Status' => ucfirst($borrow->status),
                'Tanggal Kembali (Harapan)' => $borrow->return_date_expected,
                'Disetujui Oleh' => $borrow->approver->username ?? '-',
                'Catatan' => $borrow->notes,
                'Tanggal Dibuat' => $borrow->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Peminjam',
            'Status',
            'Tanggal Kembali (Harapan)',
            'Disetujui Oleh',
            'Catatan',
            'Tanggal Dibuat'
        ];
    }
}
