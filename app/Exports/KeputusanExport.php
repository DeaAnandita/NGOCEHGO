<?php

namespace App\Exports;

use App\Models\Keputusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class KeputusanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithEvents
{
    public function collection()
    {
        return Keputusan::with([
            'jenis',
            'unit',
            'periode',
            'jabatan',
            'status',
            'metode'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'No SK',
            'Judul Keputusan',
            'Jenis',
            'Unit',
            'Periode',
            'Jabatan Penetap',
            'Tanggal Keputusan',
            'Status',
            'Metode',
        ];
    }

    public function map($k): array
    {
        return [
            $k->nomor_sk,
            $k->judul_keputusan,
            $k->jenis->jenis_keputusan ?? '-',
            $k->unit->unit_keputusan ?? '-',
            $k->periode->tahun_awal ?? '-',
            $k->jabatan->jabatan ?? '-',
            \Carbon\Carbon::parse($k->tanggal_keputusan)->format('d-m-Y'),
            $k->status->status_keputusan ?? '-',
            $k->metode->metode ?? '-',
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

                // Aktifkan filter
                $sheet->setAutoFilter("A1:$lastCol$lastRow");

                // Freeze header
                $sheet->freezePane('A2');

                // Border semua sel
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
