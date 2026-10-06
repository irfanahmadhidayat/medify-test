<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromQuery, WithHeadings, WithMapping
{
    private $no = 0;

    public function query()
    {
        return MasterItem::query()->with('kategoriItems');
    }

    public function headings(): array
    {
        return ['No', 'Kategori', 'Nama Items', 'Supplier', 'Harga', 'Laba', 'Harga Jual'];
    }

    public function map($item): array
    {
        $this->no++;

        return [
            $this->no,
            $item->kategoriItems->pluck('nama')->implode(', '),
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba,
            round($item->harga_beli + $item->harga_beli * $item->laba / 100),
        ];
    }
}