<?php

    namespace App\Filament\Resources\AdministrasiResource\Pages;

    use App\Filament\Resources\AdministrasiResource;
    use Filament\Actions;
    use Filament\Resources\Pages\ListRecords;
    use Illuminate\Support\Facades\Auth;

    class ListAdministrasis extends ListRecords
    {
        protected static string $resource = AdministrasiResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\CreateAction::make()
                    ->visible(fn () => Auth::user()->can('addAdministrasi')),
            ];
        }

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('viewAdministrasi'), 403);
        }
    }