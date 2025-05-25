<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HargaKomoditasHarianKabkotaResource\Pages;
use App\Filament\Resources\HargaKomoditasHarianKabkotaResource\RelationManagers;
use App\Models\HargaKomoditasHarianKabkota;
use App\Models\Komoditas;
use App\Models\Administrasi;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class HargaKomoditasHarianKabkotaResource extends Resource
{
    protected static ?string $model = HargaKomoditasHarianKabkota::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Harga Komoditas'; // New navigation group

    protected static ?string $navigationLabel = 'Harga Harian Kab/Kota';

    protected static ?string $slug = 'harga-komoditas/harian-kab-kota';

    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewHargaKomoditas');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kode_kab')
                    ->label('Kabupaten/Kota')
                    ->relationship('administrasi', 'nm_adm') // Assumes 'nm_adm' is the display column in Administrasi
                    ->options(Administrasi::whereNotNull('nm_adm')->pluck('nm_adm', 'kd_adm')) // Use kd_adm as value
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('waktu')
                    ->label('Waktu')
                    ->required(),
                Select::make('id_komoditas')
                    ->label('Komoditas')
                    ->relationship('komoditas', 'nama_pangan') // Assumes 'nama_pangan' is the display column in Komoditas
                    ->options(Komoditas::pluck('nama_pangan', 'id_kmd')) // Use id_kmd as value
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('source')
                    ->label('Source')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('administrasi.nm_adm') // Display name from related Administrasi model
                    ->label('Kabupaten/Kota')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('waktu')
                    ->date()
                    ->sortable(),
                TextColumn::make('komoditas.nama_pangan') // Display name from related Komoditas model
                    ->label('Komoditas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // You might want to add filters for date ranges, komoditas, or administrasi here
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->can('editHargaKomoditas')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->can('deleteHargaKomoditas')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->can('deleteHargaKomoditas')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHargaKomoditasHarianKabkotas::route('/'),
            'create' => Pages\CreateHargaKomoditasHarianKabkota::route('/create'),
            'edit' => Pages\EditHargaKomoditasHarianKabkota::route('/{record}/edit'),
        ];
    }
}