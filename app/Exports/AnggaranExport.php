<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnggaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $anggaran;
    protected $rowGroups = [];

    public function __construct($anggaran)
    {
        $this->anggaran = $anggaran;
    }

    public function collection()
    {
        return $this->anggaran;
    }

    public function headings(): array
    {
        return [
            'Unit',
            'Periode',
            'Total Anggaran',
            'Terpakai',
            'Sisa',
            'Kegiatan',
            'Sumber',
            'Nilai Kegiatan',
        ];
    }

    public function map($a): array
    {
        $total     = $a->total_anggaran;
        $terpakai  = $a->kegiatanAnggaran->sum('nilai_anggaran');
        $sisa      = $total - $terpakai;

        $startRow = count($this->rowGroups) ? collect($this->rowGroups)->sum(fn($g) => $g['rows']) + 2 : 2;

        if ($a->kegiatanAnggaran->count() == 0) {
            $this->rowGroups[] = ['start' => $startRow, 'rows' => 1];

            return [[
                $a->unit->unit_keputusan,
                $a->periode->tahun_awal,
                $total,
                $terpakai,
                $sisa,
                '-',
                '-',
                '-',
            ]];
        }

        $rows = [];
        foreach ($a->kegiatanAnggaran as $k) {
            $rows[] = [
                $a->unit->unit_keputusan,
                $a->periode->tahun_awal,
                $total,
                $terpakai,
                $sisa,
                $k->kegiatan->nama_kegiatan,
                $k->sumber->sumber_dana,
                $k->nilai_anggaran,
            ];
        }

        $this->rowGroups[] = ['start' => $startRow, 'rows' => count($rows)];

        return $rows;
    }

    // ===== MERGE CELL + STYLE =====
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                foreach ($this->rowGroups as $g) {
                    if ($g['rows'] > 1) {
                        $from = $g['start'];
                        $to   = $g['start'] + $g['rows'] - 1;

                        // Merge kolom Unit → Sisa
                        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                            $sheet->mergeCells("$col$from:$col$to");
                            $sheet->getStyle("$col$from")->getAlignment()
                                ->setVertical('center')
                                ->setHorizontal('center');
                        }
                    }
                }

                // Header style
                $sheet->getStyle("A1:H1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border semua
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:H$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin']
                    ]
                ]);

                // Format rupiah
                $sheet->getStyle("C2:E$lastRow")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("H2:H$lastRow")->getNumberFormat()->setFormatCode('#,##0');
            }
        ];
    }
}
