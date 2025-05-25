<?php

namespace App\Filament\Resources\MasterOpdResource\Pages;

use App\Filament\Resources\MasterOpdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterOpd extends EditRecord
{
    protected static string $resource = MasterOpdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
