<?php

namespace App\Filament\Resources\DiskominfoOpSpanLaporDataEntryResource\Pages;

use App\Filament\Resources\DiskominfoOpSpanLaporDataEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiskominfoOpSpanLaporDataEntry extends EditRecord
{
    protected static string $resource = DiskominfoOpSpanLaporDataEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 