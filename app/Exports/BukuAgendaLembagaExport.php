<?php

namespace App\Exports;

use App\Models\BukuAgendaLembaga;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;

class BukuAgendaLembagaExport implements
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
        $query = BukuAgendaLembaga::with('jenisAgenda');

        if ($search) {
            $query->where('agendalembaga_nomorsurat', 'like', "%{$search}%")
                ->orWhere('agendalembaga_identitassurat', 'like', "%{$search}%");
        }

        $this->data = $query->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Agenda',
            'Kode Agenda',
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
            $d->jenisAgenda->jenisagenda_umum ?? '-',
            $d->kdagendalembaga,
            $d->agendalembaga_tanggal,
            $d->agendalembaga_nomorsurat,
            $d->agendalembaga_tanggalsurat,
            $d->agendalembaga_identitassurat,
            $d->agendalembaga_isisurat,
            $d->agendalembaga_keterangan,
            $d->agendalembaga_file
                ? url('storage/' . $d->agendalembaga_file)
                : '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Header bold + center
                $sheet->getStyle("A1:J1")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Border
                $sheet->getStyle("A1:J$lastRow")->applyFromArray([
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
                    $cell = "J$row";
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
