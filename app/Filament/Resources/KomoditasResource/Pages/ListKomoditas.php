<?php

    namespace App\Filament\Resources\KomoditasResource\Pages;

    use App\Filament\Resources\KomoditasResource;
    use Filament\Actions;
    use Filament\Resources\Pages\ListRecords;
    use Illuminate\Support\Facades\Auth;

    class ListKomoditas extends ListRecords
    {
        protected static string $resource = KomoditasResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\CreateAction::make()
                    ->visible(fn () => Auth::user()->can('addKomoditas')),
            ];
        }

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('viewKomoditas'), 403);
        }
    }