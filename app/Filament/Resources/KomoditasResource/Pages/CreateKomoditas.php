<?php

    namespace App\Filament\Resources\KomoditasResource\Pages;

    use App\Filament\Resources\KomoditasResource;
    use Filament\Actions;
    use Filament\Resources\Pages\CreateRecord;
    use Illuminate\Support\Facades\Auth;

    class CreateKomoditas extends CreateRecord
    {
        protected static string $resource = KomoditasResource::class;

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('addKomoditas'), 403);
        }
    }