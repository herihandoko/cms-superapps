<?php

namespace App\Filament\Resources\KetersediaanPanganResource\Pages;

use App\Filament\Resources\KetersediaanPanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKetersediaanPangan extends EditRecord
{
    protected static string $resource = KetersediaanPanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
