<?php

namespace App\Filament\Resources\SpanSumResource\Pages;

use App\Filament\Resources\SpanSumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpanSum extends EditRecord
{
    protected static string $resource = SpanSumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
