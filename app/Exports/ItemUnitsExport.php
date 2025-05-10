<?php

namespace App\Exports;

use App\Models\ItemUnit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemUnitsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ItemUnit::with('item', 'warehouse')->get()->map(function ($unit) {
            return [
                'ID' => $unit->id,
                'SKU' => $unit->sku,
                'Barang' => $unit->item->name ?? '-',
                'Kondisi' => $unit->condition,
                'Status' => ucfirst($unit->status),
                'Jumlah' => $unit->quantity,
                'Sumber Perolehan' => $unit->acquisition_source,
                'Tgl Perolehan' => $unit->acquisition_date,
                'Gudang' => $unit->warehouse->name ?? '-',
                'QR Code' => $unit->qr_image_url,
                'Catatan' => $unit->notes,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'Barang',
            'Kondisi',
            'Status',
            'Jumlah',
            'Sumber Perolehan',
            'Tgl Perolehan',
            'Gudang',
            'QR Code',
            'Catatan'
        ];
    }
}
