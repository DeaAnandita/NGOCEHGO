<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class KegiatanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    public function collection()
    {
        return Kegiatan::with([
            'jenis',
            'unit',
            'periode',
            'status',
            'sumberDana',
            'keputusan'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kegiatan',
            'Jenis',
            'Unit',
            'Periode',
            'Status',
            'Sumber Dana',
            'Pagu Anggaran',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Lokasi',
            'Dasar Keputusan',
        ];
    }

    public function map($k): array
    {
        return [
            $k->nama_kegiatan,
            $k->jenis->jenis_kegiatan ?? '-',
            $k->unit->unit_keputusan ?? '-',
            $k->periode->tahun_awal ?? '-',
            $k->status->status_kegiatan ?? '-',
            $k->sumberDana->sumber_dana ?? '-',
            number_format($k->pagu_anggaran, 0, ',', '.'),
            $k->tgl_mulai,
            $k->tgl_selesai,
            $k->lokasi,
            $k->keputusan
                ? $k->keputusan->nomor_sk . ' - ' . $k->keputusan->judul_keputusan
                : '-',
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
