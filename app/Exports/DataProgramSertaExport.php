<?php

namespace App\Exports;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\DataProgramSerta;
use App\Models\MasterProgramSerta;
use App\Models\MasterJawabProgramSerta;

class DataProgramSertaExport
{
    public static function export()
    {
        $filename = 'data_program_serta_' . date('Ymd_His') . '.xlsx';
        $path = storage_path("app/public/{$filename}");

        $writer = SimpleExcelWriter::create($path);

        // === Ambil master program serta & jawaban (urut sesuai tampilan web) ===
        $programList = MasterProgramSerta::orderBy('kdprogramserta', 'asc')
            ->pluck('programserta', 'kdprogramserta')
            ->toArray();

        $jawabanList = MasterJawabProgramSerta::orderBy('kdjawabprogramserta', 'asc')
            ->pluck('jawabprogramserta', 'kdjawabprogramserta')
            ->toArray();

        // === Header dinamis ===
        $header = array_merge(['NIK', 'Nama Lengkap'], array_values($programList));
        $writer->addHeader($header);

        // === Ambil data urut sesuai tampilan web (misal urut nama penduduk) ===
        $dataProgram = DataProgramSerta::with('penduduk')
            ->orderByRaw('CAST(nik AS UNSIGNED) asc')
            ->get();

        // === Tambahkan baris data ===
        foreach ($dataProgram as $data) {
            $row = [
                'NIK' => $data->nik ?? '-',
                'Nama Lengkap' => $data->penduduk->penduduk_namalengkap ?? '-',
            ];

            foreach ($programList as $kode => $namaProgram) {
                $kolom = "programserta_{$kode}";
                $idJawab = $data->$kolom ?? 0;
                $row[$namaProgram] = $jawabanList[$idJawab] ?? 'TIDAK DIISI';
            }

            $writer->addRow($row);
        }

        $writer->close();
        return response()->download($path);
    }
}
