<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\DataPrasaranaDasar;

class DataPrasaranaPdfExport
{
    public static function export()
    {
        $dataList = DataPrasaranaDasar::query()
            ->leftJoin('master_statuspemilikbangunan', 'data_prasaranadasar.kdstatuspemilikbangunan', '=', 'master_statuspemilikbangunan.kdstatuspemilikbangunan')
            ->leftJoin('master_statuspemiliklahan', 'data_prasaranadasar.kdstatuspemiliklahan', '=', 'master_statuspemiliklahan.kdstatuspemiliklahan')
            ->leftJoin('master_jenisfisikbangunan', 'data_prasaranadasar.kdjenisfisikbangunan', '=', 'master_jenisfisikbangunan.kdjenisfisikbangunan')
            ->leftJoin('master_jenislantaibangunan', 'data_prasaranadasar.kdjenislantaibangunan', '=', 'master_jenislantaibangunan.kdjenislantaibangunan')
            ->leftJoin('master_kondisilantaibangunan', 'data_prasaranadasar.kdkondisilantaibangunan', '=', 'master_kondisilantaibangunan.kdkondisilantaibangunan')
            ->leftJoin('master_jenisdindingbangunan', 'data_prasaranadasar.kdjenisdindingbangunan', '=', 'master_jenisdindingbangunan.kdjenisdindingbangunan')
            ->leftJoin('master_kondisidindingbangunan', 'data_prasaranadasar.kdkondisidindingbangunan', '=', 'master_kondisidindingbangunan.kdkondisidindingbangunan')
            ->leftJoin('master_jenisatapbangunan', 'data_prasaranadasar.kdjenisatapbangunan', '=', 'master_jenisatapbangunan.kdjenisatapbangunan')
            ->leftJoin('master_kondisiatapbangunan', 'data_prasaranadasar.kdkondisiatapbangunan', '=', 'master_kondisiatapbangunan.kdkondisiatapbangunan')
            ->leftJoin('master_sumberairminum', 'data_prasaranadasar.kdsumberairminum', '=', 'master_sumberairminum.kdsumberairminum')
            ->leftJoin('master_kondisisumberair', 'data_prasaranadasar.kdkondisisumberair', '=', 'master_kondisisumberair.kdkondisisumberair')
            ->leftJoin('master_caraperolehanair', 'data_prasaranadasar.kdcaraperolehanair', '=', 'master_caraperolehanair.kdcaraperolehanair')
            ->leftJoin('master_sumberpeneranganutama', 'data_prasaranadasar.kdsumberpeneranganutama', '=', 'master_sumberpeneranganutama.kdsumberpeneranganutama')
            ->leftJoin('master_sumberdayaterpasang', 'data_prasaranadasar.kdsumberdayaterpasang', '=', 'master_sumberdayaterpasang.kdsumberdayaterpasang')
            ->leftJoin('master_bahanbakarmemasak', 'data_prasaranadasar.kdbahanbakarmemasak', '=', 'master_bahanbakarmemasak.kdbahanbakarmemasak')
            ->leftJoin('master_fasilitastempatbab', 'data_prasaranadasar.kdfasilitastempatbab', '=', 'master_fasilitastempatbab.kdfasilitastempatbab')
            ->leftJoin('master_pembuanganakhirtinja', 'data_prasaranadasar.kdpembuanganakhirtinja', '=', 'master_pembuanganakhirtinja.kdpembuanganakhirtinja')
            ->leftJoin('master_carapembuangansampah', 'data_prasaranadasar.kdcarapembuangansampah', '=', 'master_carapembuangansampah.kdcarapembuangansampah')
            ->leftJoin('master_manfaatmataair', 'data_prasaranadasar.kdmanfaatmataair', '=', 'master_manfaatmataair.kdmanfaatmataair')
            ->select(
                'data_prasaranadasar.no_kk',
                DB::raw('COALESCE(master_statuspemilikbangunan.statuspemilikbangunan, "TIDAK DIISI") as statuspemilikbangunan'),
                DB::raw('COALESCE(master_statuspemiliklahan.statuspemiliklahan, "TIDAK DIISI") as statuspemiliklahan'),
                DB::raw('COALESCE(master_jenisfisikbangunan.jenisfisikbangunan, "TIDAK DIISI") as jenisfisikbangunan'),
                DB::raw('COALESCE(master_jenislantaibangunan.jenislantaibangunan, "TIDAK DIISI") as jenislantaibangunan'),
                DB::raw('COALESCE(master_kondisilantaibangunan.kondisilantaibangunan, "TIDAK DIISI") as kondisilantaibangunan'),
                DB::raw('COALESCE(master_jenisdindingbangunan.jenisdindingbangunan, "TIDAK DIISI") as jenisdindingbangunan'),
                DB::raw('COALESCE(master_kondisidindingbangunan.kondisidindingbangunan, "TIDAK DIISI") as kondisidindingbangunan'),
                DB::raw('COALESCE(master_jenisatapbangunan.jenisatapbangunan, "TIDAK DIISI") as jenisatapbangunan'),
                DB::raw('COALESCE(master_kondisiatapbangunan.kondisiatapbangunan, "TIDAK DIISI") as kondisiatapbangunan'),
                DB::raw('COALESCE(master_sumberairminum.sumberairminum, "TIDAK DIISI") as sumberairminum'),
                DB::raw('COALESCE(master_kondisisumberair.kondisisumberair, "TIDAK DIISI") as kondisisumberair'),
                DB::raw('COALESCE(master_caraperolehanair.caraperolehanair, "TIDAK DIISI") as caraperolehanair'),
                DB::raw('COALESCE(master_sumberpeneranganutama.sumberpeneranganutama, "TIDAK DIISI") as sumberpeneranganutama'),
                DB::raw('COALESCE(master_sumberdayaterpasang.sumberdayaterpasang, "TIDAK DIISI") as sumberdayaterpasang'),
                DB::raw('COALESCE(master_bahanbakarmemasak.bahanbakarmemasak, "TIDAK DIISI") as bahanbakarmemasak'),
                DB::raw('COALESCE(master_fasilitastempatbab.fasilitastempatbab, "TIDAK DIISI") as fasilitastempatbab'),
                DB::raw('COALESCE(master_pembuanganakhirtinja.pembuanganakhirtinja, "TIDAK DIISI") as pembuanganakhirtinja'),
                DB::raw('COALESCE(master_carapembuangansampah.carapembuangansampah, "TIDAK DIISI") as carapembuangansampah'),
                DB::raw('COALESCE(master_manfaatmataair.manfaatmataair, "TIDAK DIISI") as manfaatmataair'),
                'data_prasaranadasar.prasdas_luaslantai',
                'data_prasaranadasar.prasdas_jumlahkamar'
            )
            ->get();

        $pdf = Pdf::loadView('exports.prasarana_pdf', [
            'dataList' => $dataList,
        ])->setPaper('f4', 'landscape');

        return $pdf->download('data_prasaranadasar_' . date('Ymd_His') . '.pdf');
    }
}
