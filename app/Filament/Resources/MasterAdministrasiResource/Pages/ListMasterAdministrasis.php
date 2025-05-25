<?php

namespace App\Filament\Resources\MasterAdministrasiResource\Pages;

use App\Filament\Resources\MasterAdministrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterAdministrasis extends ListRecords
{
    protected static string $resource = MasterAdministrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}