<?php

namespace App\Filament\Resources\MasterOpdResource\Pages;

use App\Filament\Resources\MasterOpdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterOpds extends ListRecords
{
    protected static string $resource = MasterOpdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
