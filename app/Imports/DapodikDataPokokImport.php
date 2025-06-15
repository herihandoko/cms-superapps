<?php

namespace App\Imports;

use App\Models\DapodikDataPokok;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DapodikDataPokokImport implements ToCollection, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected $errors = [];
    protected $rowNumber = 0;

    // Map Excel headers to database columns
    protected $headerMap = [
        'Nama Satuan Pendidikan' => 'nama_satuan_pendidikan',
        'NPSN' => 'npsn',
        'Bentuk Pendidikan' => 'bentuk_pendidikan',
        'Status Sekolah' => 'status_sekolah',
        'Alamat' => 'alamat',
        'Desa' => 'desa',
        'Kecamatan' => 'kecamatan',
        'Kabupaten Kota' => 'kabupaten_kota',
        'Lintang' => 'lintang',
        'Bujur' => 'bujur',
        'TMT Akreditasi' => 'tmt_akreditasi',
        'Akreditasi' => 'akreditasi',
        'Rombel T1' => 'rombel_t1',
        'Rombel T2' => 'rombel_t2',
        'Rombel T3' => 'rombel_t3',
        'Rombel T4' => 'rombel_t4',
        'Rombel T5' => 'rombel_t5',
        'Rombel T6' => 'rombel_t6',
        'Rombel T7' => 'rombel_t7',
        'Rombel T8' => 'rombel_t8',
        'Rombel T9' => 'rombel_t9',
        'Rombel T10' => 'rombel_t10',
        'Rombel T11' => 'rombel_t11',
        'Rombel T12' => 'rombel_t12',
        'Rombel T13' => 'rombel_t13',
        'Rombel TKA' => 'rombel_tka',
        'Rombel TKB' => 'rombel_tkb',
        'Rombel PKTA' => 'rombel_pkta',
        'Rombel PKTB' => 'rombel_pktb',
        'Rombel PKTC' => 'rombel_pktc',
        'Jumlah Rombel' => 'jumlah_rombel',
        'Peserta Didik Baru' => 'peserta_didik_baru',
        'TKA L' => 'tka_l',
        'TKA P' => 'tka_p',
        'TKB L' => 'tkb_l',
        'TKB P' => 'tkb_p',
        'T1 L' => 't1_l',
        'T1 P' => 't1_p',
        'T2 L' => 't2_l',
        'T2 P' => 't2_p',
        'T3 L' => 't3_l',
        'T3 P' => 't3_p',
        'T4 L' => 't4_l',
        'T4 P' => 't4_p',
        'T5 L' => 't5_l',
        'T5 P' => 't5_p',
        'T6 L' => 't6_l',
        'T6 P' => 't6_p',
        'T7 L' => 't7_l',
        'T7 P' => 't7_p',
        'T8 L' => 't8_l',
        'T8 P' => 't8_p',
        'T9 L' => 't9_l',
        'T9 P' => 't9_p',
        'T10 L' => 't10_l',
        'T10 P' => 't10_p',
        'T11 L' => 't11_l',
        'T11 P' => 't11_p',
        'T12 L' => 't12_l',
        'T12 P' => 't12_p',
        'T13 L' => 't13_l',
        'T13 P' => 't13_p',
        'Jumlah Ruang Kelas' => 'jumlah_ruang_kelas',
        'Guru' => 'guru',
        'Tendik' => 'tendik',
    ];

    public function collection(Collection $rows)
    {
        // file_put_contents(storage_path('logs/dapodik.log'), 'Dapodik Import: Memulai proses import, jumlah baris: ' . $rows->count() . "\n", FILE_APPEND);
        // Delete existing data
        DapodikDataPokok::truncate();

        // Insert new data
        foreach ($rows as $row) {
            $this->rowNumber++;
            
            // Log $row mentah dari Excel
            file_put_contents(storage_path('logs/dapodik.log'), 'Row ' . $this->rowNumber . ' raw: ' . json_encode($row) . "\n", FILE_APPEND);
            try {
                // Clean and prepare the data
                $data = $this->prepareData($row);
                // Log $data hasil mapping
                file_put_contents(storage_path('logs/dapodik.log'), 'Row ' . $this->rowNumber . ' data: ' . json_encode($data) . "\n", FILE_APPEND);
                
                // Validate the data
                $validator = Validator::make($data, $this->rules());
                
                if ($validator->fails()) {
                    $this->errors[] = "Row {$this->rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Create the record
                DapodikDataPokok::create($data);
            } catch (\Exception $e) {
                $this->errors[] = "Row {$this->rowNumber}: " . $e->getMessage();
            }
        }

        // If there were any errors, throw them
        if (!empty($this->errors)) {
            throw ValidationException::withMessages([
                'import' => $this->errors
            ]);
        }
    }

    protected function prepareData($row)
    {
        $data = [];
        
        // Map Excel headers to database columns
        foreach ($this->headerMap as $excelHeader => $dbColumn) {
            $value = $row[$excelHeader] ?? null;
            
            // Khusus npsn, selalu cast ke string
            if ($dbColumn === 'npsn') {
                $data[$dbColumn] = (string) $value;
            } elseif (Str::contains($dbColumn, ['rombel_', 'jumlah_', 'tka_', 'tkb_', 't1_', 't2_', 't3_', 't4_', 't5_', 't6_', 't7_', 't8_', 't9_', 't10_', 't11_', 't12_', 't13_', 'guru', 'tendik'])) {
                $data[$dbColumn] = $this->cleanNumeric($value);
            } elseif ($dbColumn === 'tmt_akreditasi') {
                $data[$dbColumn] = $this->cleanDate($value);
            } else {
                $data[$dbColumn] = $this->cleanString($value);
            }
        }

        return $data;
    }

    protected function cleanString($value)
    {
        if (is_null($value)) return '';
        return trim((string) $value);
    }

    protected function cleanNumeric($value)
    {
        if (is_null($value) || $value === '') return null;
        return (int) preg_replace('/[^0-9-]/', '', (string) $value);
    }

    protected function cleanDate($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nama_satuan_pendidikan' => 'nullable|string|max:255',
            'npsn' => 'nullable',
            'bentuk_pendidikan' => 'nullable|string|max:255',
            'status_sekolah' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten_kota' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_satuan_pendidikan.required' => 'Nama Satuan Pendidikan harus diisi',
            'npsn.required' => 'NPSN harus diisi',
            'bentuk_pendidikan.required' => 'Bentuk Pendidikan harus diisi',
            'status_sekolah.required' => 'Status Sekolah harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'desa.required' => 'Desa harus diisi',
            'kecamatan.required' => 'Kecamatan harus diisi',
            'kabupaten_kota.required' => 'Kabupaten/Kota harus diisi',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
} 