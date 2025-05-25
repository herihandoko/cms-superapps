<?php

namespace App\Filament\Resources\MasterAdministrasiResource\Pages;

use App\Filament\Resources\MasterAdministrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMasterAdministrasi extends ViewRecord
{
    protected static string $resource = MasterAdministrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}