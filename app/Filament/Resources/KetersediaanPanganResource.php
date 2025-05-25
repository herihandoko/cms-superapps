<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KetersediaanPanganResource\Pages;
use App\Filament\Resources\KetersediaanPanganResource\RelationManagers;
use App\Models\KetersediaanPangan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model; // Added for permission checks
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth; // Added for permission checks

class KetersediaanPanganResource extends Resource
{
    protected static ?string $model = KetersediaanPangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells'; // Changed icon

    protected static ?string $navigationGroup = 'Harga Komoditas';

    protected static ?string $navigationLabel = 'Ketersediaan Pangan';

    protected static ?int $navigationSort = 2; // Assuming it comes after 'Harga Harian Kab/Kota'

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_pangan')
                    ->label('Nama Pangan')
                    ->required()
                    ->maxLength(35)
                    ->columnSpanFull(),
                TextInput::make('proyeksi')
                    ->label('Proyeksi')
                    ->numeric()
                    ->default(0),
                TextInput::make('neraca')
                    ->label('Neraca')
                    ->numeric()
                    ->default(0),
                TextInput::make('output')
                    ->label('Output')
                    ->numeric()
                    ->default(0),
                TextInput::make('ketersediaan')
                    ->label('Ketersediaan')
                    ->maxLength(25),
                DatePicker::make('periode')
                    ->label('Periode')
                    ->required(),
                TextInput::make('source')
                    ->label('Sumber Data')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pangan')
                    ->label('Nama Pangan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('proyeksi')
                    ->label('Proyeksi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('neraca')
                    ->label('Neraca')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('output')
                    ->label('Output')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ketersediaan')
                    ->label('Ketersediaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->date()
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Sumber Data')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListKetersediaanPangans::route('/'),
            'create' => Pages\CreateKetersediaanPangan::route('/create'),
            'edit' => Pages\EditKetersediaanPangan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewKetersediaanPangan');
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('addKetersediaanPangan');
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('editKetersediaanPangan');
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteKetersediaanPangan');
    }

    public static function canDeleteAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteKetersediaanPangan');
    }
}
