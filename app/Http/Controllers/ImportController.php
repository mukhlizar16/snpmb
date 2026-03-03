<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessImportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    private const ALLOWED_TYPES = [
        'ref_jurusan', 'ref_mata_pelajaran', 'ref_mp_pendukung', 'ref_index_sekolah', 'ref_portofolio', 'data_sekolah',
        'data_siswa', 'data_pilihan', 'data_prestasi',
        'data_nilai', 'data_nilai_tka', 'data_portofolio', 'data_status_tambahan',
    ];

    public function index()
    {
        return view('import.index');
    }

    // ── AJAX: terima file → dispatch job → kembalikan job_id ──────────────────

    public function importAjax(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:51200',
            'type' => 'required|string|in:'.implode(',', self::ALLOWED_TYPES),
        ], [
            'file.required' => 'File wajib diupload.',
            'file.uploaded' => 'Upload gagal — ukuran file melebihi batas server (64 MB). Pastikan server sudah dikonfigurasi dengan benar.',
            'file.mimes' => 'File harus berformat CSV atau XLSX.',
            'file.max' => 'Ukuran file maksimal 50 MB.',
            'type.in' => 'Tipe import tidak valid.',
        ]);

        $path = $request->file('file')->store('imports');
        $jobId = Str::uuid()->toString();

        Cache::put("import_job_{$jobId}", [
            'status' => 'queued',
            'type' => $request->input('type'),
            'result' => null,
        ], now()->addHours(2));

        ProcessImportJob::dispatch($jobId, $path, $request->input('type'));

        return response()->json(['job_id' => $jobId]);
    }

    // ── Polling: cek status job ────────────────────────────────────────────────

    public function importStatus(string $jobId): JsonResponse
    {
        if (! preg_match('/^[0-9a-f\-]{36}$/i', $jobId)) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $data = Cache::get("import_job_{$jobId}");

        if (! $data) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json($data);
    }

    // ── Download error log ─────────────────────────────────────────────────────

    public function downloadError(string $file)
    {
        return response()->download(Storage::disk('public')->path($file));
    }
}
