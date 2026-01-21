<?php

namespace App\Exports;

use App\Models\AgendaKelembagaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class AgendaExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    protected $agenda;

    public function __construct($agenda)
    {
        $this->agenda = $agenda;
    }

    public function collection()
    {
        return $this->agenda;
    }

    public function headings(): array
    {
        return [
            'Judul',
            'Jenis',
            'Unit',
            'Tanggal',
            'Jam',
            'Tempat',
            'Status',
        ];
    }

    public function map($a): array
    {
        return [
            $a->judul_agenda,
            $a->jenis->jenis_agenda ?? '-',
            $a->unit->unit_keputusan ?? '-',
            \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y'),
            $a->jam_mulai . ' - ' . ($a->jam_selesai ?? '-'),
            $a->tempat->tempat_agenda ?? '-',
            $a->status->status_agenda ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
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

                // Filter
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
