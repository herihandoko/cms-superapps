<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CustomersChart; // Added
use App\Filament\Widgets\LatestSpanSumWidget; // Added
use App\Filament\Widgets\OrdersChart; // Added
use App\Filament\Widgets\StatsOverviewWidget; // Added
use App\Models\MasterOpd; // Added for OPD selection
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('kode_opd')
                            ->label('Nama Opd')
                            ->options(MasterOpd::pluck('nama_opd', 'id')) // Assuming 'id' is the key in master_opd
                            ->searchable()
                            ->preload(),
                        DatePicker::make('startDate')
                            ->label('Tanggal Mulai')
                            ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                        DatePicker::make('endDate')
                            ->label('Tanggal Akhir')
                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                    ])
                    ->columns(3),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            OrdersChart::class,
            CustomersChart::class,
            LatestSpanSumWidget::class, // Replaced LatestOrders
        ];
    }
}
