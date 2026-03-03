<?php

namespace App\Services\Import;

use App\Services\Import\Handlers\BulkInsertHandler;
use App\Services\Import\Validators\DataNilaiRowValidator;
use App\Services\Import\Validators\DataNilaiTkaRowValidator;
use App\Services\Import\Validators\DataPortofolioRowValidator;
use App\Services\Import\Validators\DataPrestasiRowValidator;
use App\Services\Import\Validators\DataSekolahRowValidator;
use App\Services\Import\Validators\DataStatusTambahanRowValidator;
use App\Services\Import\Validators\PilihanRowValidator;
use App\Services\Import\Validators\RefJurusanRowValidator;
use App\Services\Import\Validators\RefMataPelajaranRowValidator;
use App\Services\Import\Validators\RefIndexSekolahRowValidator;
use App\Services\Import\Validators\RefMpPendukungRowValidator;
use App\Services\Import\Validators\RefPortofolioRowValidator;
use App\Services\Import\Validators\SiswaRowValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportService
{
    protected BulkInsertHandler $bulkInsert;

    protected array $validators = [
        'ref_jurusan' => RefJurusanRowValidator::class,
        'ref_mata_pelajaran' => RefMataPelajaranRowValidator::class,
        'ref_mp_pendukung' => RefMpPendukungRowValidator::class,
        'ref_index_sekolah' => RefIndexSekolahRowValidator::class,
        'ref_portofolio' => RefPortofolioRowValidator::class,
        'data_sekolah' => DataSekolahRowValidator::class,
        'data_siswa' => SiswaRowValidator::class,
        'data_pilihan' => PilihanRowValidator::class,
        'data_prestasi' => DataPrestasiRowValidator::class,
        'data_nilai' => DataNilaiRowValidator::class,
        'data_nilai_tka' => DataNilaiTkaRowValidator::class,
        'data_portofolio' => DataPortofolioRowValidator::class,
        'data_status_tambahan' => DataStatusTambahanRowValidator::class,
    ];

    public function __construct(BulkInsertHandler $bulkInsert)
    {
        $this->bulkInsert = $bulkInsert;
    }

    public function handle(string $filePath, string $type): array
    {
        if (! isset($this->validators[$type])) {
            throw new \Exception("Tipe import tidak dikenali: {$type}");
        }

        $validatorClass = $this->validators[$type];
        $validator = new $validatorClass;
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $inserted = 0;
        $errors = [];
        $buffer = [];

        DB::beginTransaction();

        try {
            if ($this->bulkInsert->needsTruncate($type)) {
                DB::table($type)->delete();
            }

            if ($extension === 'csv' || $extension === 'txt') {
                $fullPath = Storage::disk('local')->path($filePath);
                $handle = fopen($fullPath, 'r');

                // Deteksi delimiter dan strip UTF-8 BOM dari baris pertama
                $firstLine = ltrim(fgets($handle), "\xEF\xBB\xBF");
                $delimiter = str_contains($firstLine, ';') ? ';' : (str_contains($firstLine, "\t") ? "\t" : ',');
                rewind($handle);

                $index = 0;

                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    if ($index++ === 0) {
                        continue; // skip header
                    }

                    $result = $this->validateRow($validator, $row, $index);

                    if ($result['status'] === true) {
                        $buffer[] = $result['data'];

                        if (\count($buffer) >= BulkInsertHandler::CHUNK_SIZE) {
                            $inserted += $this->bulkInsert->insert($type, $buffer);
                            $buffer = [];
                        }
                    } elseif ($result['status'] !== 'skip') {
                        $errors[] = $result['message'];
                    }
                }

                fclose($handle);
            } else {
                // XLSX: load ke array (ukuran file diharapkan lebih kecil)
                $rows = Excel::toArray(new \stdClass, $filePath)[0];

                foreach ($rows as $index => $row) {
                    if ($index === 0) {
                        continue; // skip header
                    }

                    $result = $this->validateRow($validator, $row, $index);

                    if ($result['status'] === true) {
                        $buffer[] = $result['data'];

                        if (\count($buffer) >= BulkInsertHandler::CHUNK_SIZE) {
                            $inserted += $this->bulkInsert->insert($type, $buffer);
                            $buffer = [];
                        }
                    } elseif ($result['status'] !== 'skip') {
                        $errors[] = $result['message'];
                    }
                }
            }

            // flush sisa buffer
            if (! empty($buffer)) {
                $inserted += $this->bulkInsert->insert($type, $buffer);
            }

            DB::commit();

            return [
                'inserted' => $inserted,
                'errors' => $errors,
                'error_file' => \count($errors) ? $this->generateErrorFile($errors, $type) : null,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'inserted' => 0,
                'errors' => [$e->getMessage()],
                'error_file' => null,
            ];
        }
    }

    protected function validateRow(object $validator, array $row, int $index): array
    {
        try {
            $data = $validator->validate($row);

            if ($data === null) {
                return ['status' => 'skip'];
            }

            if (! $data) {
                return ['status' => false, 'message' => "Baris ke-{$index}: data tidak valid."];
            }

            return ['status' => true, 'data' => $data];

        } catch (\Exception $e) {
            return ['status' => false, 'message' => "Baris ke-{$index}: {$e->getMessage()}"];
        }
    }

    protected function generateErrorFile(array $errors, string $type): string
    {
        $filename = "error_import_{$type}_".now()->timestamp.'.csv';
        $path = storage_path("app/public/{$filename}");

        $handle = fopen($path, 'w');
        foreach ($errors as $error) {
            fputcsv($handle, [$error]);
        }
        fclose($handle);

        return $filename;
    }
}
