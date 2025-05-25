<?php

namespace App\Filament\Resources\DisdukcapilOpPendudukResource\Pages;

use App\Filament\Resources\DisdukcapilOpPendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisdukcapilOpPenduduks extends ListRecords
{
    protected static string $resource = DisdukcapilOpPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}