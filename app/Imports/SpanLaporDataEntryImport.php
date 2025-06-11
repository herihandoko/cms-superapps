<?php

namespace App\Imports;

use App\Models\DiskominfoPengaduanEntry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;

class SpanLaporDataEntryImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    use Importable;

    public function __construct()
    {
        // Truncate the table before import
        DB::connection('superapps')->table('diskominfo_op_span_lapor_data_entry')->truncate();
    }

    public function model(array $row)
    {
        // Convert header names to lowercase and remove special characters
        $cleanRow = [];
        foreach ($row as $key => $value) {
            $cleanKey = Str::of($key)
                ->lower()
                ->replaceMatches('/[^a-z0-9]/', '_')
                ->toString();
            $cleanRow[$cleanKey] = $value;
        }

        return new DiskominfoPengaduanEntry([
            'unit_kerja' => $cleanRow['unit_kerja'] ?? '',
            'belum_terverifikasi' => $this->parseNumeric($cleanRow['belum_terverifikasi'] ?? 0),
            'belum_ditindaklanjuti' => $this->parseNumeric($cleanRow['belum_ditindaklanjuti'] ?? 0),
            'proses' => $this->parseNumeric($cleanRow['proses'] ?? 0),
            'selesai' => $this->parseNumeric($cleanRow['selesai'] ?? 0),
            'total' => $this->parseNumeric($cleanRow['total'] ?? 0),
            'persentase_tl' => $this->parsePercentage($cleanRow['_tl'] ?? 0), // %TL becomes _tl
            'rtl' => $this->parseNumeric($cleanRow['rtl'] ?? 0),
            'rhp' => $this->parseNumeric($cleanRow['rhp'] ?? 0),
        ]);
    }

    private function parseNumeric($value)
    {
        if (empty($value)) return 0;
        
        // Remove any non-numeric characters except decimal point
        $number = preg_replace('/[^0-9.]/', '', str_replace(',', '.', $value));
        return floatval($number);
    }

    private function parsePercentage($value)
    {
        if (empty($value)) return 0;
        
        // Remove % sign and convert to decimal
        $number = str_replace(['%', ','], ['', '.'], $value);
        return floatval($number);
    }

    public function rules(): array
    {
        return [
            '*.unit_kerja' => 'required|string',
            // Make all numeric fields optional as they'll be converted to 0 if empty
            '*.belum_terverifikasi' => 'nullable',
            '*.belum_ditindaklanjuti' => 'nullable',
            '*.proses' => 'nullable',
            '*.selesai' => 'nullable',
            '*.total' => 'nullable',
            '*._tl' => 'nullable',
            '*.rtl' => 'nullable',
            '*.rhp' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'unit_kerja.required' => 'Kolom Unit Kerja harus diisi',
        ];
    }

    // Process records in batches of 100
    public function batchSize(): int
    {
        return 100;
    }

    // Read records in chunks of 100
    public function chunkSize(): int
    {
        return 100;
    }
} 