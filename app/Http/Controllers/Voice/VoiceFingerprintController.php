<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VoiceFingerprint; // Pastikan model ini ada
use Illuminate\Support\Facades\DB;

class VoiceFingerprintController extends Controller
{
    /**
     * Cek apakah fingerprint suara sudah ada duplikat di database
     */
    public function checkDuplicate(Request $request)
    {
        $request->validate([
            'fingerprint' => 'required|array|min:20', // minimal panjang vector
            'fingerprint.*' => 'numeric'
        ]);

        $newFp = $request->input('fingerprint');
        $threshold = 0.88; // Bisa di-adjust setelah testing (0.85 - 0.92 biasanya baik)

        // Optimasi: Jika data banyak, gunakan query lebih efisien daripada all()
        $existingFingerprints = VoiceFingerprint::select('fingerprint')->get();

        foreach ($existingFingerprints as $item) {
            $existingFp = $item->fingerprint; // sudah otomatis jadi array karena casting

            // Pastikan panjang vector sama
            if (count($existingFp) !== count($newFp)) {
                continue;
            }

            $similarity = $this->cosineSimilarity($newFp, $existingFp);

            if ($similarity > $threshold) {
                return response()->json([
                    'duplicate' => true,
                    'similarity' => round($similarity, 4),
                    'message' => 'Suara terdeteksi mirip dengan data sebelumnya.'
                ]);
            }
        }

        return response()->json([
            'duplicate' => false,
            'message' => 'Suara baru, boleh melanjutkan pendataan.'
        ]);
    }

    /**
     * Fungsi cosine similarity
     */
    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dot = 0.0;
        $magA = 0.0;
        $magB = 0.0;

        foreach ($vecA as $i => $valueA) {
            $valueB = $vecB[$i] ?? 0;

            $dot += $valueA * $valueB;
            $magA += $valueA ** 2;
            $magB += $valueB ** 2;
        }

        if ($magA == 0 || $magB == 0) {
            return 0.0;
        }

        return $dot / (sqrt($magA) * sqrt($magB));
    }

    /**
     * Simpan fingerprint suara (bisa dipanggil dari controller lain)
     */
    public static function storeFingerprint(Request $request, $keluargaId)
    {
        if ($request->has('voice_fingerprint')) {
            $fingerprint = $request->input('voice_fingerprint');

            // Validasi sederhana
            if (is_array($fingerprint) && count($fingerprint) > 10) {
                VoiceFingerprint::create([
                    'keluarga_id' => $keluargaId,
                    'fingerprint' => $fingerprint // akan otomatis jadi JSON
                ]);
            }
        }
    }
}