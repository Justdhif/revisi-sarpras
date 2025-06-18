<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ItemsExport implements FromCollection, WithHeadings, WithDrawings, WithColumnWidths
{
    protected $filters;
    protected $items;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;

        $this->items = Item::with('category')
            ->when($this->filters['search'] ?? null, function ($query) {
                $query->where('name', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when($this->filters['category'] ?? null, function ($query) {
                $query->where('category_id', $this->filters['category']);
            })
            ->when($this->filters['type'] ?? null, function ($query) {
                $query->where('type', $this->filters['type']);
            })
            ->latest()
            ->take(50) // batas jumlah export untuk performa
            ->get();
    }

    public function collection()
    {
        return $this->items->map(function ($item) {
            return [
                $item->name,
                ucfirst($item->type),
                $item->category->name ?? '-',
                $item->description ?? '-',
                $item->created_at->format('d-m-Y'),
                '', // Placeholder untuk gambar
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

        foreach ($this->items as $index => $item) {
            if (!$item->image_url)
                continue;

            $path = public_path($item->image_url);
            if (!file_exists($path))
                continue;

            $drawing = new Drawing();
            $drawing->setName('Image');
            $drawing->setDescription('Item Image');
            $drawing->setPath($path);
            $drawing->setHeight(60);
            $drawing->setCoordinates('F' . ($index + 2)); // Kolom gambar dimulai dari baris 2
            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // Nama
            'B' => 15, // Tipe
            'C' => 20, // Kategori
            'D' => 40, // Deskripsi
            'E' => 15, // Tanggal
            'F' => 15, // Gambar
        ];
    }
}
