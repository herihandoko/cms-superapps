<?php

namespace App\Filament\Resources\SpanSumResource\Pages;

use App\Filament\Resources\SpanSumResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpanSum extends ViewRecord
{
    protected static string $resource = SpanSumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}