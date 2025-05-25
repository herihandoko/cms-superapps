<?php

namespace App\Filament\Resources\SpanSumResource\Pages;

use App\Filament\Resources\SpanSumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpanSums extends ListRecords
{
    protected static string $resource = SpanSumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
