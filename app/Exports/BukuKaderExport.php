<?php

namespace App\Exports;

use App\Models\BukuKader;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class BukuKaderExport implements
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
        $query = BukuKader::with(['pendidikan', 'bidang', 'status', 'penduduk']);

        if ($this->search) {
            $query->whereHas('penduduk', function ($q) {
                $q->where('nama', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Kode', 'Tanggal', 'Nama Penduduk', 'Pendidikan', 'Bidang', 'Status', 'Keterangan'];
    }

    public function map($k): array
    {
        return [
            $k->kdkader,
            $k->kader_tanggal,
            $k->penduduk->nama ?? '-',
            $k->pendidikan->pendidikan ?? '-',
            $k->bidang->bidang ?? '-',
            $k->status->statuskader ?? '-',
            $k->kader_keterangan
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
