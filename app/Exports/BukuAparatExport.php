<?php

namespace App\Exports;

use App\Models\BukuAparat;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithDrawings,
    WithCustomStartCell
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;

class BukuAparatExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    WithDrawings,
    WithCustomStartCell
{
    protected $data;
    protected $rowMap = [];

    public function __construct($status = null)
    {
        $query = BukuAparat::with('masterAparat');

        if ($status) {
            $query->where('statusaparatdesa', $status);
        }

        $this->data = $query->get();
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Foto',
            'Aparat',
            'NIP',
            'NIK',
            'Pangkat',
            'Nomor SK',
            'Tanggal',
            'Status',
            'Keterangan'
        ];
    }

    public function map($d): array
    {
        // simpan index row utk foto
        $this->rowMap[] = $d->id;

        return [
            '', // kolom foto (kosong → diisi drawing)
            $d->masterAparat->aparat ?? '-',
            " " . $d->nipaparat,
            " " . $d->nik,
            $d->pangkataparat,
            $d->nomorpengangkatan,
            $d->tanggalpengangkatan,
            $d->statusaparatdesa,
            $d->keteranganaparatdesa,
        ];
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->data as $i => $d) {

            if (!$d->fotopengangkatan) continue;

            $path = public_path('storage/' . $d->fotopengangkatan);
            if (!file_exists($path)) continue;

            $drawing = new Drawing();
            $drawing->setPath($path);
            $drawing->setHeight(60);
            $drawing->setCoordinates('A' . ($i + 2)); // kolom Foto
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // NIP
            'D' => NumberFormat::FORMAT_TEXT, // NIK
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();
                // Rata tengah semua isi sel (vertical & horizontal)
                $sheet->getStyle("A2:$lastCol$lastRow")->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                // Tinggi baris agar muat foto
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(70);
                }

                // Lebar kolom Foto
                $sheet->getColumnDimension('A')->setWidth(15);

                // Auto filter
                $sheet->setAutoFilter("A1:$lastCol$lastRow");

                // Freeze header
                $sheet->freezePane('A2');

                // Border
                $sheet->getStyle("A1:$lastCol$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);
            }
        ];
    }
}
