<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SpanSumResource;
use App\Models\SpanSum;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class LatestSpanSumWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3; // Assuming this comes after the charts

    public function getTableHeading(): string
    {
        return 'Summary Aduan Terbaru';
    }

    public function table(Table $table): Table
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now(); // Default to now if no end date

        $kodeOpd = $this->filters['kode_opd'] ?? null;

        $query = SpanSumResource::getEloquentQuery();

        if ($kodeOpd) {
            $query->where('kode_opd', $kodeOpd);
        }

        if ($startDate) {
            $query->where('tgl_aduan', '>=', $startDate);
        }
        // Ensure endDate is inclusive
        $query->where('tgl_aduan', '<=', $endDate->endOfDay());


        return $table
            ->query($query)
            ->defaultPaginationPageOption(5)
            ->defaultSort('tgl_aduan', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('masterOpd.nama_opd')
                    ->label('Nama OPD')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_aduan')
                    ->label('Tanggal Aduan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_aduan')
                    ->label('Total Aduan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proses')
                    ->label('Proses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selesai')
                    ->label('Selesai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('belum_terverifikasi')
                    ->label('Belum Verifikasi')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('belum_ditindaklanjuti')
                    ->label('Belum Tindaklanjut')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (SpanSum $record): string => SpanSumResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}