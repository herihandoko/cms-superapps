<?php

    namespace App\Filament\Resources\HargaKomoditasHarianKabkotaResource\Pages;

    use App\Filament\Resources\HargaKomoditasHarianKabkotaResource;
    use Filament\Actions;
    use Filament\Resources\Pages\CreateRecord;
    use Illuminate\Support\Facades\Auth;

    class CreateHargaKomoditasHarianKabkota extends CreateRecord
    {
        protected static string $resource = HargaKomoditasHarianKabkotaResource::class;

        protected function authorizeAccess(): void
        {
            parent::authorizeAccess();
            abort_unless(Auth::user()->can('addHargaKomoditas'), 403);
        }
    }