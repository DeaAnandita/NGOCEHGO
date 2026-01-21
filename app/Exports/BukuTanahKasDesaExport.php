<?php

namespace App\Exports;

use App\Models\BukuTanahKasDesa;
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

class BukuTanahKasDesaExport implements
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
        $query = BukuTanahKasDesa::with(['perolehan', 'jenis', 'patok', 'papanNama']);

        if ($search) {
            $query->where('kdtanahkasdesa', 'like', "%$search%")
                ->orWhere('lokasitanahkasdesa', 'like', "%$search%");
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
            'Asal',
            'Sertifikat',
            'Luas',
            'Kelas',
            'Tanggal',
            'Perolehan',
            'Jenis',
            'Patok',
            'Papan Nama',
            'Lokasi',
            'Peruntukan',
            'Mutasi',
            'Keterangan'
        ];
    }

    public function map($d): array
    {
        return [
            '', // kolom foto
            $d->kdtanahkasdesa,
            $d->asaltanahkasdesa,
            " " . $d->sertifikattanahkasdesa,
            $d->luastanahkasdesa,
            $d->kelastanahkasdesa,
            $d->tanggaltanahkasdesa,
            $d->perolehan->perolehantkd ?? '-',
            $d->jenis->jenistkd ?? '-',
            $d->patok->patok ?? '-',
            $d->papanNama->papannama ?? '-',
            $d->lokasitanahkasdesa,
            $d->peruntukantanahkasdesa,
            $d->mutasitanahkasdesa,
            $d->keterangantanahkasdesa,
        ];
    }

    // FOTO TANAH
    public function drawings()
    {
        $drawings = [];

        foreach ($this->data as $i => $d) {

            if (!$d->fototanahkasdesa) continue;

            $path = public_path('storage/' . $d->fototanahkasdesa);
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
            'D' => NumberFormat::FORMAT_TEXT, // Sertifikat
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

                // Center + wrap
                $sheet->getStyle("A2:$lastCol$lastRow")->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Tinggi baris
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(70);
                }

                // Lebar kolom foto
                $sheet->getColumnDimension('A')->setWidth(18);

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
