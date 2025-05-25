<?php

namespace App\Filament\Resources\DisdukcapilOpPendudukResource\Pages;

use App\Filament\Resources\DisdukcapilOpPendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisdukcapilOpPenduduk extends EditRecord
{
    protected static string $resource = DisdukcapilOpPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}