<?php

    namespace App\Filament\Resources\AdministrasiResource\Pages;

    use App\Filament\Resources\AdministrasiResource;
    use Filament\Actions;
    use Filament\Resources\Pages\EditRecord;
    use Illuminate\Support\Facades\Auth;

    class EditAdministrasi extends EditRecord
    {
        protected static string $resource = AdministrasiResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->can('deleteAdministrasi')),
            ];
        }

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('editAdministrasi'), 403);
        }
    }