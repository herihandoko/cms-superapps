<?php

namespace App\Filament\Resources\DisdukcapilOpPendudukResource\Pages;

use App\Filament\Resources\DisdukcapilOpPendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDisdukcapilOpPenduduk extends ViewRecord
{
    protected static string $resource = DisdukcapilOpPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}