<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PencairanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $data;
    protected $rowGroups = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Kegiatan',
            'Tanggal Cair',
            'Jumlah Cair',
            'Total Realisasi',
            'Sisa',
            'Tanggal Realisasi',
            'Uraian',
            'Jumlah Realisasi'
        ];
    }
    public function map($p): array
    {
        $totalRealisasi = $p->realisasiPengeluaran->sum('jumlah');
        $sisa = $p->jumlah - $totalRealisasi;

        $startRow = count($this->rowGroups)
            ? collect($this->rowGroups)->sum(fn($g) => $g['rows']) + 2
            : 2;

        if ($p->realisasiPengeluaran->count() == 0) {
            $this->rowGroups[] = ['start' => $startRow, 'rows' => 1];

            return [
                $p->kegiatan->nama_kegiatan,
                $p->tanggal_cair,
                $p->jumlah,
                $totalRealisasi,
                $sisa,
                '-',
                '-',
                '-'
            ];
        }

        $rows = [];
        foreach ($p->realisasiPengeluaran as $r) {
            $rows[] = [
                $p->kegiatan->nama_kegiatan,
                $p->tanggal_cair,
                $p->jumlah,
                $totalRealisasi,
                $sisa,
                $r->tanggal,
                $r->uraian,
                $r->jumlah
            ];
        }

        $this->rowGroups[] = ['start' => $startRow, 'rows' => count($rows)];

        return $rows[0]; // Laravel Excel ambil baris pertama, sisanya di-handle via WithEvents
    }


    // =================== FORMAT EXCEL ===================
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Merge kolom pencairan
                foreach ($this->rowGroups as $g) {
                    if ($g['rows'] > 1) {
                        $from = $g['start'];
                        $to   = $g['start'] + $g['rows'] - 1;

                        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                            $sheet->mergeCells("$col$from:$col$to");
                            $sheet->getStyle("$col$from")->getAlignment()
                                ->setVertical('center')
                                ->setHorizontal('center');
                        }
                    }
                }

                // Header
                $sheet->getStyle("A1:H1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:H$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin']
                    ]
                ]);

                // Format Rupiah
                $sheet->getStyle("C2:E$lastRow")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("H2:H$lastRow")->getNumberFormat()->setFormatCode('#,##0');
            }
        ];
    }
}
