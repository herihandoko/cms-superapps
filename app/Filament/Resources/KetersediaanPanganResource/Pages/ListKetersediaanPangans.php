<?php

namespace App\Filament\Resources\KetersediaanPanganResource\Pages;

use App\Filament\Resources\KetersediaanPanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKetersediaanPangans extends ListRecords
{
    protected static string $resource = KetersediaanPanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
