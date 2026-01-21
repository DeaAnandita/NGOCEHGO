<?php

namespace App\Exports;

use App\Models\BukuPeraturan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BukuPeraturanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
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
            'Kode',
            'Jenis Peraturan',
            'Nomor',
            'Judul',
            'Uraian',
            'Kesepakatan',
            'Keterangan',
            'User Input',
            'Waktu',
            'File'
        ];
    }

    public function map($d): array
    {
        $this->no++;

        return [
            $this->no,
            $d->kdperaturan,
            $d->jenisPeraturanDesa->jenisperaturandesa ?? '-',
            $d->nomorperaturan,
            $d->judulpengaturan,
            $d->uraianperaturan,
            $d->kesepakatanperaturan,
            $d->keteranganperaturan,
            $d->userinput,
            $d->inputtime,
            $d->filepengaturan
                ? url('storage/' . $d->filepengaturan)   // hyperlink ke PDF
                : '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Header bold & center
                $sheet->getStyle("A1:K1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border semua sel
                $sheet->getStyle("A1:K$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin']
                    ]
                ]);

                // Center kolom No
                $sheet->getStyle("A2:A$lastRow")->getAlignment()->setHorizontal('center');

                // Kolom File jadi hyperlink
                for ($row = 2; $row <= $lastRow; $row++) {
                    $cell = "K$row";
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
