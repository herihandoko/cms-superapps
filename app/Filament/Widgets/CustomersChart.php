<?php

namespace App\Filament\Widgets;

use App\Models\SpanSum; // Added
use Carbon\Carbon; // Added
use Carbon\CarbonPeriod; // Added
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters; // Added
use Illuminate\Support\Facades\DB; // Added

class CustomersChart extends ChartWidget
{
    use InteractsWithPageFilters; // Added

    protected static ?string $heading = 'Total Aduan'; // Changed

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            Carbon::now()->subYear()->startOfMonth();

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $kodeOpd = $this->filters['kode_opd'] ?? null;

        $query = SpanSum::query()
            ->select(
                DB::raw('SUM(total_aduan) as total_aduan_sum'),
                DB::raw("DATE_FORMAT(tgl_aduan, '%Y-%m') as month_year")
            )
            ->where('tgl_aduan', '<=', $endDate->endOfDay()) // Consider all data up to the end date for cumulative sum
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc');

        if ($kodeOpd) {
            $query->where('kode_opd', $kodeOpd);
        }
        
        // If a start date is provided, filter records from that start date for the trend
        if (!is_null($this->filters['startDate'] ?? null)) {
             $query->where('tgl_aduan', '>=', $startDate->startOfDay());
        }


        $monthlyAduan = $query->get()->keyBy('month_year');

        $labels = [];
        $data = [];
        $cumulativeTotal = 0;

        $period = CarbonPeriod::create($startDate->startOfMonth(), '1 month', $endDate->endOfMonth());

        foreach ($period as $date) {
            $monthYear = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            if (isset($monthlyAduan[$monthYear])) {
                $cumulativeTotal += $monthlyAduan[$monthYear]->total_aduan_sum;
            }
            $data[] = $cumulativeTotal;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Aduan (Kumulatif)', // Changed
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => 'rgb(54, 162, 235)', // Example color
                    'tension' => 0.1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
