<?php

namespace App\Exports;

use App\Models\BukuInventaris;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithDrawings,
    ShouldAutoSize
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BukuInventarisExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithDrawings,
    ShouldAutoSize
{
    protected $data;
    protected $no = 0;

    public function __construct($search = null)
    {
        $q = BukuInventaris::with(['pengguna', 'satuan', 'asalBarang'])
            ->where('inventaris_hapus', 0);

        if ($search) {
            $q->where('kdinventaris', 'like', "%$search%")
                ->orWhere('inventaris_identitas', 'like', "%$search%");
        }

        $this->data = $q->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Foto',
            'No',
            'Kode',
            'Tanggal',
            'Pengguna',
            'Identitas',
            'Volume',
            'Satuan',
            'Asal',
            'Harga',
            'Keterangan'
        ];
    }

    public function map($d): array
    {
        $this->no++;
        return [
            '',
            $this->no,
            $d->kdinventaris,
            $d->inventaris_tanggal,
            $d->pengguna->pengguna ?? '-',
            $d->inventaris_identitas,
            $d->inventaris_volume,
            $d->satuan->satuanbarang ?? '-',
            $d->asalBarang->asalbarang ?? '-',
            $d->inventaris_harga,
            $d->inventaris_keterangan
        ];
    }

    public function drawings()
    {
        $drawings = [];
        foreach ($this->data as $i => $d) {
            if (!$d->inventaris_foto) continue;
            $path = public_path('storage/' . $d->inventaris_foto);
            if (!file_exists($path)) continue;

            $img = new Drawing();
            $img->setPath($path);
            $img->setHeight(60);
            $img->setCoordinates('A' . ($i + 2));
            $drawings[] = $img;
        }
        return $drawings;
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $e) {
            $sheet = $e->sheet->getDelegate();
            $last = $sheet->getHighestRow();
            $sheet->getRowDimension(1)->setRowHeight(30);
            for ($i = 2; $i <= $last; $i++) $sheet->getRowDimension($i)->setRowHeight(70);
            $sheet->getStyle("A1:K$last")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ]);
        }];
    }
}
