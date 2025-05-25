<?php

namespace App\Filament\Resources\DiskominfoOpTopTenSpanResource\Pages;

use App\Filament\Resources\DiskominfoOpTopTenSpanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiskominfoOpTopTenSpans extends ListRecords
{
    protected static string $resource = DiskominfoOpTopTenSpanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}