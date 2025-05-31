<?php

namespace App\Filament\Resources\DiskominfoOpSpanLaporV2Resource\Pages;

use App\Filament\Resources\DiskominfoOpSpanLaporV2Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiskominfoOpSpanLaporV2s extends ListRecords
{
    protected static string $resource = DiskominfoOpSpanLaporV2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 