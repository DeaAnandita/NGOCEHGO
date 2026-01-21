<?php

namespace App\Exports;

use App\Models\BukuProyek;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class BukuProyekExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = BukuProyek::with(['kegiatan', 'pelaksana', 'lokasi', 'sumber']);

        if ($this->search) {
            $query->where('kdproyek', 'like', "%{$this->search}%");
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Kode', 'Tanggal', 'Kegiatan', 'Pelaksana', 'Lokasi', 'Sumber Dana', 'Nominal', 'Manfaat', 'Keterangan'];
    }

    public function map($p): array
    {
        return [
            $p->kdproyek,
            $p->proyek_tanggal,
            $p->kegiatan->kegiatan ?? '-',
            $p->pelaksana->pelaksana ?? '-',
            $p->lokasi->lokasi ?? '-',
            $p->sumber->sumber_dana ?? '-',
            $p->proyek_nominal,
            $p->proyek_manfaat,
            $p->proyek_keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']]];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();
                $sheet->setAutoFilter("A1:$lastCol$lastRow");
                $sheet->freezePane('A2');
                $sheet->getStyle("A1:$lastCol$lastRow")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => 'thin']]
                ]);
            }
        ];
    }
}
