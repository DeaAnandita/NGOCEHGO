<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LPJExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $data;
    protected $no = 0;

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
            'No',
            'Kegiatan',
            'Total Anggaran',
            'Total Realisasi',
            'Sisa',
            'Status',
            'File LPJ'
        ];
    }

    public function map($d): array
    {
        $this->no++;

        return [
            $this->no, // nomor urut rapi
            $d->kegiatan->nama_kegiatan,
            $d->total_anggaran,
            $d->total_realisasi,
            $d->sisa_anggaran,
            match ($d->status) {
                1 => 'Diajukan',
                2 => 'Disetujui',
                3 => 'Ditolak',
                default => '-'
            },
            $d->file_lpj
                ? url('storage/' . $d->file_lpj)   // LINK KLIKABLE
                : '-'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Header bold & center
                $sheet->getStyle("A1:G1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border semua sel
                $sheet->getStyle("A1:G$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin']
                    ]
                ]);

                // Format rupiah
                $sheet->getStyle("C2:E$lastRow")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Center kolom No & Status
                $sheet->getStyle("A2:A$lastRow")->getAlignment()->setHorizontal('center');
                $sheet->getStyle("F2:F$lastRow")->getAlignment()->setHorizontal('center');

                // Kolom File LPJ jadi hyperlink
                for ($row = 2; $row <= $lastRow; $row++) {
                    $cell = "G$row";
                    $value = $sheet->getCell($cell)->getValue();
                    if ($value && $value !== '-') {
                        $sheet->getCell($cell)->getHyperlink()->setUrl($value);
                        $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF0000FF');
                        $sheet->getStyle($cell)->getFont()->setUnderline(true);
                    }
                }
            }
        ];
    }
}
