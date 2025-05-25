<?php

namespace App\Filament\Widgets;

use App\Models\SpanSum; // Added
use Carbon\Carbon; // Added
use Carbon\CarbonPeriod; // Added
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters; // Added
use Illuminate\Support\Facades\DB; // Added

class OrdersChart extends ChartWidget
{
    use InteractsWithPageFilters; // Added

    protected static ?string $heading = 'Aduan per bulan'; // Changed

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            Carbon::now()->subYear()->startOfMonth(); // Default to last 12 months if no start date

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $kodeOpd = $this->filters['kode_opd'] ?? null;

        $query = SpanSum::query()
            ->select(
                DB::raw('SUM(total_aduan) as total_aduan_sum'),
                DB::raw("DATE_FORMAT(tgl_aduan, '%Y-%m') as month_year")
            )
            ->whereBetween('tgl_aduan', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->groupBy('month_year')
            ->orderBy('month_year', 'asc');

        if ($kodeOpd) {
            $query->where('kode_opd', $kodeOpd);
        }

        $aduanData = $query->get();

        $labels = [];
        $data = [];

        // Create a period to iterate over months for complete labels
        $period = CarbonPeriod::create($startDate->startOfMonth(), '1 month', $endDate->endOfMonth());
        $monthlyTotals = $aduanData->keyBy('month_year');

        foreach ($period as $date) {
            $monthYear = $date->format('Y-m');
            $labels[] = $date->format('M Y'); // e.g., Jan 2023
            $data[] = $monthlyTotals[$monthYear]->total_aduan_sum ?? 0;
        }


        return [
            'datasets' => [
                [
                    'label' => 'Total Aduan', // Changed
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => 'rgb(75, 192, 192)', // Example color
                    'tension' => 0.1, // Example tension
                ],
            ],
            'labels' => $labels,
        ];
    }
}
