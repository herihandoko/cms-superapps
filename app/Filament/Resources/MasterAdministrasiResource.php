<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterAdministrasiResource\Pages;
use App\Models\MasterAdministrasi;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MasterAdministrasiResource extends Resource
{
    protected static ?string $model = MasterAdministrasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Dukcapil';

    protected static ?string $navigationLabel = 'Master Administrasi';

    protected static ?string $slug = 'dukcapil/master-administrasi';

    // You might want to add a sort order if there are other items in the 'Dukcapil' group
    // protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kd_adm')
                    ->label('Kode Administrasi')
                    ->maxLength(255),
                TextInput::make('wilayah_adm')
                    ->label('Wilayah Administrasi')
                    ->maxLength(255),
                TextInput::make('nm_adm')
                    ->label('Nama Administrasi')
                    ->maxLength(255),
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
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('kd_adm')
                    ->label('Kode Administrasi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('wilayah_adm')
                    ->label('Wilayah Administrasi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nm_adm')
                    ->label('Nama Administrasi')
                    ->searchable()
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMasterAdministrasis::route('/'),
            'create' => Pages\CreateMasterAdministrasi::route('/create'),
            'view' => Pages\ViewMasterAdministrasi::route('/{record}'),
            'edit' => Pages\EditMasterAdministrasi::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewMasterAdministrasiDukcapil');
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('addMasterAdministrasiDukcapil');
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('editMasterAdministrasiDukcapil');
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteMasterAdministrasiDukcapil');
    }

    public static function canDeleteAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteMasterAdministrasiDukcapil');
    }

     public static function canView(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewMasterAdministrasiDukcapil');
    }
}