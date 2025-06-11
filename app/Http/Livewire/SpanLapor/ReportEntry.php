<?php

namespace App\Http\Livewire\SpanLapor;

use Livewire\Component;
use App\Models\DiskominfoPengaduanEntry;
use Livewire\WithPagination;

class ReportEntry extends Component
{
    use WithPagination;

    public $unit_kerja;
    public $belum_terverifikasi = 0;
    public $belum_ditindaklanjuti = 0;
    public $proses = 0;
    public $selesai = 0;
    public $total = 0;
    public $persentase_tl = 0;
    public $rtl = 0;
    public $rhp = 0;

    protected $rules = [
        'unit_kerja' => 'required|string|max:255',
        'belum_terverifikasi' => 'required|integer|min:0',
        'belum_ditindaklanjuti' => 'required|integer|min:0',
        'proses' => 'required|integer|min:0',
        'selesai' => 'required|integer|min:0',
        'total' => 'required|integer|min:0',
        'persentase_tl' => 'required|numeric|min:0|max:100',
        'rtl' => 'required|integer|min:0',
        'rhp' => 'required|integer|min:0',
    ];

    public function render()
    {
        $entries = DiskominfoPengaduanEntry::paginate(10);
        return view('livewire.span-lapor.report-entry', [
            'entries' => $entries
        ]);
    }

    public function save()
    {
        $this->validate();

        DiskominfoPengaduanEntry::create([
            'unit_kerja' => $this->unit_kerja,
            'belum_terverifikasi' => $this->belum_terverifikasi,
            'belum_ditindaklanjuti' => $this->belum_ditindaklanjuti,
            'proses' => $this->proses,
            'selesai' => $this->selesai,
            'total' => $this->total,
            'persentase_tl' => $this->persentase_tl,
            'rtl' => $this->rtl,
            'rhp' => $this->rhp,
        ]);

        $this->reset();
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function calculateTotal()
    {
        $this->total = $this->belum_terverifikasi + 
                      $this->belum_ditindaklanjuti + 
                      $this->proses + 
                      $this->selesai;
                      
        if ($this->total > 0) {
            $this->persentase_tl = round(($this->selesai / $this->total) * 100, 2);
        } else {
            $this->persentase_tl = 0;
        }
    }
} 