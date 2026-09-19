<?php

namespace App\Exports;

use App\Models\DoorprizeWinner;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WinnersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $sesi;
    protected $search;
    private $rowNumber = 0;

    public function __construct($sesi = null, $search = null)
    {
        $this->sesi   = $sesi;
        $this->search = $search;
    }

    public function query()
    {
        return DoorprizeWinner::with(['participant', 'prize'])
            // Filter Sesi
            ->when($this->sesi && $this->sesi !== 'all', function ($query) {
                $query->whereHas('prize', function ($q) {
                    $q->where('sesi', $this->sesi)
                      ->orWhere('sesi', 'like', "%{$this->sesi}%");
                });
            })
            // Filter Pencarian
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('participant', function ($qp) {
                        $qp->where('name', 'like', "%{$this->search}%")
                           ->orWhere('npk', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('prize', function ($qpz) {
                        $qpz->where('name', 'like', "%{$this->search}%");
                    });
                });
            })
            ->orderBy('won_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'NPK',
            'Nama Pemenang',
            'Status Karyawan',
            'Hadiah',
            'Sesi Undian',
        ];
    }

    public function map($winner): array
    {
        $this->rowNumber++;

        $rawSesi = $winner->prize->sesi ?? $winner->sesi ?? 1;
        $sesiNum = (int) preg_replace('/[^0-9]/', '', (string) $rawSesi);

        return [
            $this->rowNumber,
            $winner->participant->npk ?? '-',
            $winner->participant->name ?? 'N/A',
            $winner->participant->status_karyawan ?? 'N/A',
            $winner->prize->name ?? 'Hadiah Doorprize',
            'Sesi ' . ($sesiNum ?: 1),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold pada baris Header (Baris 1)
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}