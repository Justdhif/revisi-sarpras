<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemsExport implements FromCollection, WithHeadings, WithDrawings
{
    public function collection()
    {
        return Item::with('category')->get()->map(function ($item) {
            return [
                $item->name,
                $item->type,
                $item->category->name ?? '-',
                $item->description,
                $item->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Tipe',
            'Kategori',
            'Deskripsi',
            'Tanggal',
            'Gambar',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $items = Item::take(50)->get(); // batasi agar tidak terlalu berat

        foreach ($items as $index => $item) {
            if (!$item->image_url)
                continue;

            $drawing = new Drawing();
            $drawing->setName('Image');
            $drawing->setDescription('Item Image');
            $drawing->setPath(public_path($item->image_url));
            $drawing->setHeight(60);
            $drawing->setCoordinates('F' . ($index + 2)); // gambar di kolom F
            $drawings[] = $drawing;
        }

        return $drawings;
    }
}
