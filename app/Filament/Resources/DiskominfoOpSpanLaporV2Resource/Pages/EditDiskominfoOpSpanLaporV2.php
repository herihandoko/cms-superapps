<?php

namespace App\Filament\Resources\DiskominfoOpSpanLaporV2Resource\Pages;

use App\Filament\Resources\DiskominfoOpSpanLaporV2Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiskominfoOpSpanLaporV2 extends EditRecord
{
    protected static string $resource = DiskominfoOpSpanLaporV2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 