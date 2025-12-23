<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
// Jika Anda menggunakan library eksternal untuk ekstraksi embedding, import di sini
// Contoh: use SomeLibrary\VoiceEmbeddingExtractor;

class VoiceController extends Controller
{
    /**
     * Validasi voice print untuk mencegah duplikasi pendataan.
     * Dipanggil dari frontend saat pertanyaan pertama (No. KK).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request)
    {
        // Validasi file audio
        $request->validate([
            'voice_sample' => 'required|file|mimes:webm,ogg,wav,mp3|max:20480', // max 20MB
        ]);

        try {
            $file = $request->file('voice_sample');
            $extension = $file->getClientOriginalExtension();
            $filename = 'voice_validation_' . Str::random(40) . '.' . $extension;

            // Simpan sementara di storage/app/public/voice_samples (buat folder jika belum ada)
            $path = $file->storeAs('voice_samples', $filename, 'public');

            $fullPath = storage_path('app/public/' . $path);

            // === EKSTRAKSI VOICE EMBEDDING / FEATURE ===
            // Di sini Anda perlu implementasi ekstraksi voice print.
            // Pilihan populer:
            // 1. Gunakan library Python (misalnya ECAPA-TDNN dari SpeechBrain) via process exec.
            // 2. Gunakan API eksternal seperti Microsoft Azure Speaker Recognition, AssemblyAI, atau Deepgram.
            // 3. Gunakan model open-source seperti Titanet atau Resemble AI (memerlukan setup server).

            // Contoh sederhana placeholder (GANTI DENGAN IMPLEMENTASI NYATA)
            $currentEmbedding = $this->extractVoiceEmbedding($fullPath);

            if (!$currentEmbedding) {
                return response()->json([
                    'allowed' => false,
                    'message' => 'Gagal memproses sampel suara. Silakan coba lagi.'
                ], 422);
            }

            // === CEK DATABASE UNTUK MATCH ===
            $threshold = 0.6; // Cosine similarity threshold (sesuaikan setelah testing)

            $existingVoices = DB::table('voice_prints')->get();

            foreach ($existingVoices as $record) {
                $similarity = $this->cosineSimilarity($currentEmbedding, json_decode($record->embedding, true));

                if ($similarity >= $threshold) {
                    // Hapus file sementara
                    Storage::disk('public')->delete($path);

                    return response()->json([
                        'allowed' => false,
                        'message' => 'Suara Anda sudah terdaftar sebelumnya. Pendataan tidak dapat dilanjutkan untuk mencegah duplikasi data keluarga.'
                    ]);
                }
            }

            // Jika lolos (belum terdaftar), simpan voice print untuk pendataan berikutnya
            DB::table('voice_prints')->insert([
                'embedding' => json_encode($currentEmbedding),
                'filename' => $filename,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Hapus file sementara (opsional, bisa disimpan untuk audit)
            Storage::disk('public')->delete($path);

            return response()->json([
                'allowed' => true,
                'message' => 'Validasi suara berhasil. Pendataan dapat dilanjutkan.'
            ]);

        } catch (\Exception $e) {
            Log::error('Voice validation error: ' . $e->getMessage());

            return response()->json([
                'allowed' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    /**
     * Placeholder untuk ekstraksi voice embedding.
     * GANTI DENGAN IMPLEMENTASI NYATA.
     */
    private function extractVoiceEmbedding(string $audioPath): ?array
    {
        // Contoh menggunakan library eksternal atau exec ke Python script
        // Misalnya dengan SpeechBrain atau Titanet (pre-trained speaker embedding model)

        // Contoh dummy (vektor 512 dimensi acak)
        // return array_fill(0, 512, mt_rand() / mt_getrandmax());

        // Rekomendasi implementasi nyata:
        // 1. Install Python + SpeechBrain
        // 2. Buat script python extract_embedding.py yang mengembalikan JSON embedding
        // 3. Jalankan: exec("python3 extract_embedding.py {$audioPath}", $output);
        // 4. return json_decode(implode("\n", $output), true);

        return null; // Ganti dengan return embedding array
    }

    /**
     * Hitung cosine similarity antara dua vektor embedding.
     */
    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < count($vecA); $i++) {
            $dotProduct += $vecA[$i] * $vecB[$i];
            $normA += $vecA[$i] ** 2;
            $normB += $vecB[$i] ** 2;
        }

        if ($normA == 0 || $normB == 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Simpan semua data keluarga (method yang sudah ada di aplikasi Anda).
     */
    public function storeAll(Request $request)
    {
        // Logika penyimpanan 12 modul data keluarga Anda yang sudah ada
        // ...

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);
    }
}