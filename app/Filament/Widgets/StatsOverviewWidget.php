<?php

namespace App\Filament\Widgets;

use App\Models\SpanSum; // Added for data fetching
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $kodeOpd = $this->filters['kode_opd'] ?? null;

        $query = SpanSum::query();

        if ($kodeOpd) {
            $query->where('kode_opd', $kodeOpd);
        }

        if ($startDate) {
            $query->where('tgl_aduan', '>=', $startDate);
        }
        // Ensure endDate is inclusive if it's the same day or for filtering up to the end of that day
        $query->where('tgl_aduan', '<=', $endDate->endOfDay());


        // Clone the query for different aggregates to avoid issues
        $totalAduan = (clone $query)->sum('total_aduan');
        $proses = (clone $query)->sum('proses');
        $selesai = (clone $query)->sum('selesai');

        $formatNumber = function ($number): string { // Allow float/int
            if (is_null($number)) {
                return '0';
            }
            $number = (float) $number;
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'k';
            }

            return Number::format($number / 1000000, 2) . 'm';
        };

        return [
            Stat::make('Total Aduan', $formatNumber($totalAduan))
                // ->description('Description here') // Placeholder
                // ->descriptionIcon('heroicon-m-arrow-trending-up') // Placeholder
                // ->chart([7, 2, 10, 3, 15, 4, 17]) // Placeholder
                ->color('primary'),
            Stat::make('Proses', $formatNumber($proses))
                // ->description('Description here') // Placeholder
                // ->descriptionIcon('heroicon-m-arrow-trending-down') // Placeholder
                // ->chart([17, 16, 14, 15, 14, 13, 12]) // Placeholder
                ->color('warning'),
            Stat::make('Selesai', $formatNumber($selesai))
                // ->description('Description here') // Placeholder
                // ->descriptionIcon('heroicon-m-arrow-trending-up') // Placeholder
                // ->chart([15, 4, 10, 2, 12, 4, 12]) // Placeholder
                ->color('success'),
        ];
    }
}
