<?php

    namespace App\Filament\Resources\HargaKomoditasHarianKabkotaResource\Pages;

    use App\Filament\Resources\HargaKomoditasHarianKabkotaResource;
    use Filament\Actions;
    use Filament\Resources\Pages\EditRecord;
    use Illuminate\Support\Facades\Auth;

    class EditHargaKomoditasHarianKabkota extends EditRecord
    {
        protected static string $resource = HargaKomoditasHarianKabkotaResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->can('deleteHargaKomoditas')),
            ];
        }

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('editHargaKomoditas'), 403);
        }
    }