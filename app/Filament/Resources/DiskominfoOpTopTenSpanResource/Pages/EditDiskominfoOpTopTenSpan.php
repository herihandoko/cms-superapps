<?php

namespace App\Filament\Resources\DiskominfoOpTopTenSpanResource\Pages;

use App\Filament\Resources\DiskominfoOpTopTenSpanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiskominfoOpTopTenSpan extends EditRecord
{
    protected static string $resource = DiskominfoOpTopTenSpanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}