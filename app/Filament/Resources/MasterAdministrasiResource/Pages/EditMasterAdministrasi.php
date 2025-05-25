<?php

namespace App\Filament\Resources\MasterAdministrasiResource\Pages;

use App\Filament\Resources\MasterAdministrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterAdministrasi extends EditRecord
{
    protected static string $resource = MasterAdministrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}