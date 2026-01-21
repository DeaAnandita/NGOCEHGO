<?php

namespace App\Exports;

use App\Models\BukuTanahDesa;
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

class BukuTanahDesaExport implements
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

    public function __construct($search = null)
    {
        $query = BukuTanahDesa::with(['statusHak','penggunaan','mutasi','jenisPemilik']);

        if ($search) {
            $query->where('kdtanahdesa', 'like', "%$search%")
                  ->orWhere('pemiliktanahdesa', 'like', "%$search%");
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
            'Kode',
            'Tanggal',
            'Jenis Pemilik',
            'Pemilik',
            'Kode Pemilik',
            'Luas',
            'Status Hak',
            'Penggunaan',
            'Mutasi',
            'Tanggal Mutasi',
            'Keterangan'
        ];
    }

    public function map($d): array
    {
        return [
            '',
            $d->kdtanahdesa,
            $d->tanggaltanahdesa,
            $d->jenisPemilik->jenispemilik ?? '-',
            $d->pemiliktanahdesa,
            " " . $d->kdpemilik,
            $d->luastanahdesa,
            $d->statusHak->statushaktanah ?? '-',
            $d->penggunaan->penggunaantanah ?? '-',
            $d->mutasi->mutasitanah ?? '-',
            $d->tanggalmutasitanahdesa,
            $d->keterangantanahdesa,
        ];
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->data as $i => $d) {

            if (!$d->fototanahdesa) continue;

            $path = public_path('storage/' . $d->fototanahdesa);
            if (!file_exists($path)) continue;

            $drawing = new Drawing();
            $drawing->setPath($path);
            $drawing->setHeight(60);
            $drawing->setCoordinates('A' . ($i + 2));
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT, // kode pemilik
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

                $sheet->getStyle("A2:$lastCol$lastRow")->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(70);
                }

                $sheet->getColumnDimension('A')->setWidth(18);
                $sheet->setAutoFilter("A1:$lastCol$lastRow");
                $sheet->freezePane('A2');

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
