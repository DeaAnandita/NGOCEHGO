<?php

namespace App\Exports;

use App\Models\PengurusKelembagaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;

class PengurusExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    public function collection()
    {
        return PengurusKelembagaan::with([
            'unit',
            'jabatan',
            'status',
            'jenisSk',
            'periodeAwal',
            'periodeAkhir'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Induk',
            'Nama Lengkap',
            'Jenis Kelamin',
            'No HP',
            'Email',
            'Alamat',
            'Unit',
            'Jabatan',
            'Periode',
            'Status',
            'Jenis SK',
            'No SK',
            'Tanggal SK',
            'Keterangan'
        ];
    }

    public function map($p): array
    {
        // Konversi jenis kelamin
        $jk = $p->jenis_kelamin == 'L' ? 'Laki-laki' : ($p->jenis_kelamin == 'P' ? 'Perempuan' : '-');

        return [
            " " . $p->nomor_induk,        // NIK anti E+
            $p->nama_lengkap,
            $jk,
            " " . $p->no_hp,             // HP anti E+
            $p->email,
            $p->alamat,
            $p->unit->nama_unit ?? '-',
            $p->jabatan->jabatan ?? '-',
            ($p->periodeAwal->tahun_awal ?? '') . ' - ' . ($p->periodeAkhir->akhir ?? ''),
            $p->status->status_pengurus ?? '-',
            $p->jenisSk->jenis_sk ?? '-',
            $p->no_sk,
            $p->tanggal_sk,
            $p->keterangan,
        ];
    }

    // Tetap kita set sebagai TEXT
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }

    // Style header
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

    // Border, freeze header, filter
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
