<?php

namespace App\Jobs;

use App\Services\Import\ImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 menit

    public int $tries = 1;   // no retry — data integrity

    public function __construct(
        public readonly string $jobId,
        public readonly string $filePath,
        public readonly string $type,
    ) {}

    public function handle(ImportService $importService): void
    {
        Cache::put("import_job_{$this->jobId}", [
            'status' => 'processing',
            'type' => $this->type,
            'result' => null,
        ], now()->addHours(2));

        if ($this->type === 'data_nilai') {
            ini_set('memory_limit', '512M');
            set_time_limit(600);
        }

        try {
            $result = $importService->handle($this->filePath, $this->type);

            Cache::put("import_job_{$this->jobId}", [
                'status' => 'done',
                'type' => $this->type,
                'result' => $result,
            ], now()->addHours(2));

        } catch (\Exception $e) {
            Cache::put("import_job_{$this->jobId}", [
                'status' => 'failed',
                'type' => $this->type,
                'result' => [
                    'inserted' => 0,
                    'errors' => [$e->getMessage()],
                    'error_file' => null,
                ],
            ], now()->addHours(2));
        }
    }

    public function failed(\Throwable $e): void
    {
        Cache::put("import_job_{$this->jobId}", [
            'status' => 'failed',
            'type' => $this->type,
            'result' => [
                'inserted' => 0,
                'errors' => [$e->getMessage()],
                'error_file' => null,
            ],
        ], now()->addHours(2));
    }
}
