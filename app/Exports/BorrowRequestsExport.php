<?php

namespace App\Exports;

use App\Models\BorrowRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowRequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item'])
            ->get()
            ->map(function ($borrow) {
                $items = $borrow->borrowDetails->map(function ($detail) {
                    $itemName = $detail->itemUnit->item->name ?? '-';
                    $sku = $detail->itemUnit->sku ?? '-';
                    $qty = $detail->quantity ?? 0;

                    return "{$itemName} (SKU: {$sku}, Qty: {$qty})";
                })->implode('; ');

                return [
                    'ID' => $borrow->id,
                    'Peminjam' => $borrow->user->username ?? '-',
                    'Status' => ucfirst($borrow->status),
                    'Tanggal Pinjam' => $borrow->borrow_date_expected,
                    'Tanggal Kembali (Harapan)' => $borrow->return_date_expected,
                    'Dihandle Oleh' => $borrow->handler->username ?? '-',
                    'Catatan' => $borrow->notes,
                    'Tanggal Dibuat' => $borrow->created_at->format('Y-m-d H:i'),
                    'Barang Dipinjam' => $items,
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
            'Dihandle Oleh',
            'Catatan',
            'Tanggal Dibuat',
            'Barang Dipinjam',
        ];
    }
}
