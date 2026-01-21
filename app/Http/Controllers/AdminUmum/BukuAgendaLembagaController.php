<?php

namespace App\Http\Controllers\AdminUmum;

use App\Exports\BukuAgendaLembagaExport;
use App\Http\Controllers\Controller;
use App\Models\BukuAgendaLembaga;
use App\Models\MasterJenisAgendaUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class BukuAgendaLembagaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $data = BukuAgendaLembaga::with('jenisAgenda')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('agendalembaga_nomorsurat', 'like', "%{$search}%")
                        ->orWhere('agendalembaga_identitassurat', 'like', "%{$search}%");
                });
            })
            ->orderBy('agendalembaga_tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin_umum.agenda_kelembagaan.index', compact('data', 'search'));
    }

    public function create()
    {
        $jenis = MasterJenisAgendaUmum::orderBy('jenisagenda_umum')->get();
        return view('admin_umum.agenda_kelembagaan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kdagendalembaga' => 'required|unique:buku_agendalembaga,kdagendalembaga',
            'kdjenisagenda_umum' => 'required',
            'agendalembaga_tanggal' => 'required|date',
            'agendalembaga_identitassurat' => 'required',
            'agendalembaga_file' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        $data = $request->only([
            'kdagendalembaga',
            'kdjenisagenda_umum',
            'agendalembaga_tanggal',
            'agendalembaga_nomorsurat',
            'agendalembaga_tanggalsurat',
            'agendalembaga_identitassurat',
            'agendalembaga_isisurat',
            'agendalembaga_keterangan'
        ]);

        if ($request->hasFile('agendalembaga_file')) {
            $data['agendalembaga_file'] = $request->file('agendalembaga_file')
                ->store('agenda', 'public');
        }

        $data['userinput'] = Auth::user()->name ?? 'system';
        $data['inputtime'] = now();

        BukuAgendaLembaga::create($data);

        return redirect()->route('admin-umum.agenda.index')
            ->with('success', 'Agenda berhasil disimpan');
    }




    public function show($id)
    {
        $data = BukuAgendaLembaga::with('jenisAgenda')
            ->where('kdagendalembaga', $id)
            ->firstOrFail();

        return view('admin_umum.agenda_kelembagaan.show', compact('data'));
    }

    public function edit($id)
    {
        $data = BukuAgendaLembaga::where('kdagendalembaga', $id)->firstOrFail();
        $jenis = MasterJenisAgendaUmum::orderBy('jenisagenda_umum')->get();

        return view('admin_umum.agenda_kelembagaan.edit', compact('data', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $agenda = BukuAgendaLembaga::where('kdagendalembaga', $id)->firstOrFail();

        $request->validate([
            'kdjenisagenda_umum' => 'required',
            'agendalembaga_tanggal' => 'required|date',
            'agendalembaga_identitassurat' => 'required',
            'agendalembaga_file' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        $data = $request->only([
            'kdjenisagenda_umum',
            'agendalembaga_tanggal',
            'agendalembaga_nomorsurat',
            'agendalembaga_tanggalsurat',
            'agendalembaga_identitassurat',
            'agendalembaga_isisurat',
            'agendalembaga_keterangan'
        ]);

        if ($request->hasFile('agendalembaga_file')) {
            if ($agenda->agendalembaga_file) {
                Storage::disk('public')->delete($agenda->agendalembaga_file);
            }

            $data['agendalembaga_file'] = $request->file('agendalembaga_file')
                ->store('agenda', 'public');
        }

        $agenda->update($data);

        return redirect()->route('admin-umum.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui');
    }


    public function destroy($id)
    {
        BukuAgendaLembaga::where('kdagendalembaga', $id)->delete();

        return redirect()->route('admin-umum.agenda.index')
            ->with('success', 'Agenda berhasil dihapus');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new BukuAgendaLembagaExport($request->search),
            'buku_agenda_lembaga.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = BukuAgendaLembaga::with('jenisAgenda');

        if ($request->search) {
            $query->where('agendalembaga_nomorsurat', 'like', "%{$request->search}%")
                ->orWhere('agendalembaga_identitassurat', 'like', "%{$request->search}%");
        }

        $data = $query->get();

        // 1. Buat PDF Rekap
        $rekapPdf = Pdf::loadView('admin_umum.agenda_kelembagaan.export_pdf', [
            'data'   => $data,
            'search' => $request->search
        ])->setPaper('A4', 'landscape')->output();

        $tmpDir = storage_path('app/tmp');
        if (!file_exists($tmpDir)) mkdir($tmpDir, 0777, true);

        $rekapPath = $tmpDir . '/rekap_agenda.pdf';
        file_put_contents($rekapPath, $rekapPdf);

        // 2. Gabungkan semua PDF
        $pdf = new Fpdi();

        // Masukkan Rekap
        $rekapPages = $pdf->setSourceFile($rekapPath);
        for ($i = 1; $i <= $rekapPages; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);
        }

        // 3. Masukkan semua file surat
        foreach ($data as $d) {

            if (!$d->agendalembaga_file) continue;

            $file = storage_path('app/public/' . $d->agendalembaga_file);
            if (!file_exists($file)) continue;

            $pageCount = 0;
            $tmp = null;

            try {
                $pageCount = $pdf->setSourceFile($file);
            } catch (\Throwable $e) {
                $pageCount = 0;
            }

            // Kalau gagal → convert PDF modern
            if ($pageCount < 1) {
                $tmp = storage_path('app/tmp/' . uniqid() . '.pdf');
                $this->normalizePdf($file, $tmp);

                if (file_exists($tmp)) {
                    try {
                        $pageCount = $pdf->setSourceFile($tmp);
                        $file = $tmp;
                    } catch (\Throwable $e) {
                        $pageCount = 0;
                    }
                }
            }

            // Kalau tetap gagal → skip
            if ($pageCount < 1) {
                if ($tmp && file_exists($tmp)) unlink($tmp);
                continue;
            }

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }

            if ($tmp && file_exists($tmp)) unlink($tmp);
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="buku_agenda_lembaga.pdf"');
    }
    private function normalizePdf($input, $output)
    {
        $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH '
            . '-sOutputFile="' . $output . '" "' . $input . '"';

        exec($cmd);
    }
}
