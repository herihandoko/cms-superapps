<?php

    namespace App\Filament\Resources\HargaKomoditasHarianKabkotaResource\Pages;

    use App\Filament\Resources\HargaKomoditasHarianKabkotaResource;
    use Filament\Actions;
    use Filament\Resources\Pages\ListRecords;
    use Illuminate\Support\Facades\Auth;

    class ListHargaKomoditasHarianKabkotas extends ListRecords
    {
        protected static string $resource = HargaKomoditasHarianKabkotaResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\CreateAction::make()
                    ->visible(fn () => Auth::user()->can('addHargaKomoditas')),
            ];
        }

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('viewHargaKomoditas'), 403);
        }
    }