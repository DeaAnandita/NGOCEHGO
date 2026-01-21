<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BukuKeputusanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
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
            'Jenis Keputusan',
            'Nomor',
            'Tanggal',
            'Judul',
            'Uraian',
            'Keterangan',
            'File'
        ];
    }

    public function map($d): array
    {
        $this->no++;

        return [
            $this->no,
            $d->jenisKeputusan->jeniskeputusan_umum ?? '-',
            $d->nomor_keputusan,
            $d->tanggal_keputusan,
            $d->judul_keputusan,
            $d->uraian_keputusan,
            $d->keterangan_keputusan,
            $d->file_keputusan
                ? url('storage/' . $d->file_keputusan)
                : '-'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Header bold
                $sheet->getStyle("A1:H1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border
                $sheet->getStyle("A1:H$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin']
                    ]
                ]);

                // Center No
                $sheet->getStyle("A2:A$lastRow")->getAlignment()->setHorizontal('center');

                // Hyperlink file
                for ($row = 2; $row <= $lastRow; $row++) {
                    $cell = "H$row";
                    $value = $sheet->getCell($cell)->getValue();
                    if ($value && $value !== '-') {
                        $sheet->getCell($cell)->getHyperlink()->setUrl($value);
                        $sheet->getStyle($cell)->getFont()->setUnderline(true)->getColor()->setARGB('FF0000FF');
                    }
                }
            }
        ];
    }
}
