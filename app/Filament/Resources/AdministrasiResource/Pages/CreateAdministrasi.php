<?php

    namespace App\Filament\Resources\AdministrasiResource\Pages;

    use App\Filament\Resources\AdministrasiResource;
    use Filament\Actions;
    use Filament\Resources\Pages\CreateRecord;
    use Illuminate\Support\Facades\Auth;

    class CreateAdministrasi extends CreateRecord
    {
        protected static string $resource = AdministrasiResource::class;

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('addAdministrasi'), 403);
        }
    }