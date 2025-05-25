<?php

    namespace App\Filament\Resources\KomoditasResource\Pages;

    use App\Filament\Resources\KomoditasResource;
    use Filament\Actions;
    use Filament\Resources\Pages\EditRecord;
    use Illuminate\Support\Facades\Auth;

    class EditKomoditas extends EditRecord
    {
        protected static string $resource = KomoditasResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->can('deleteKomoditas')),
            ];
        }

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('editKomoditas'), 403);
        }
    }