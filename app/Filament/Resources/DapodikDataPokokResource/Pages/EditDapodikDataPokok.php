<?php

namespace App\Filament\Resources\DapodikDataPokokResource\Pages;

use App\Filament\Resources\DapodikDataPokokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDapodikDataPokok extends EditRecord
{
    protected static string $resource = DapodikDataPokokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
} 