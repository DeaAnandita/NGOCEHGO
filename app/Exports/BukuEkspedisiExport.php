<?php

namespace App\Exports;

use App\Models\BukuEkspedisi;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;

class BukuEkspedisiExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
{
    protected $data;
    protected $no = 0;

    public function __construct($search = null)
    {
        $query = BukuEkspedisi::query();

        if ($search) {
            $query->where('ekspedisi_nomorsurat', 'like', "%$search%")
                ->orWhere('ekspedisi_identitassurat', 'like', "%$search%");
        }

        $this->data = $query->orderBy('ekspedisi_tanggal')->get();
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
            'Tanggal',
            'No Surat',
            'Tanggal Surat',
            'Identitas Surat',
            'Isi Surat',
            'Keterangan',
            'File'
        ];
    }

    public function map($d): array
    {
        $this->no++;

        return [
            $this->no,
            $d->kdekspedisi,
            $d->ekspedisi_tanggal,
            $d->ekspedisi_nomorsurat,
            $d->ekspedisi_tanggalsurat,
            $d->ekspedisi_identitassurat,
            $d->ekspedisi_isisurat,
            $d->ekspedisi_keterangan,
            $d->ekspedisi_file
                ? url('storage/' . $d->ekspedisi_file)
                : '-'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Header bold + center
                $sheet->getStyle("A1:I1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border
                $sheet->getStyle("A1:I$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => 'thin']
                    ]
                ]);

                // Center kolom No
                $sheet->getStyle("A2:A$lastRow")
                    ->getAlignment()
                    ->setHorizontal('center');

                // Hyperlink file
                for ($row = 2; $row <= $lastRow; $row++) {
                    $cell = "I$row";
                    $value = $sheet->getCell($cell)->getValue();
                    if ($value && $value !== '-') {
                        $sheet->getCell($cell)->getHyperlink()->setUrl($value);
                        $sheet->getStyle($cell)
                            ->getFont()
                            ->setUnderline(true)
                            ->getColor()
                            ->setARGB('FF0000FF');
                    }
                }
            }
        ];
    }
}
