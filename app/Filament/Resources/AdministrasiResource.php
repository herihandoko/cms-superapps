<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrasiResource\Pages;
use App\Filament\Resources\AdministrasiResource\RelationManagers;
use App\Models\Administrasi;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AdministrasiResource extends Resource
{
    protected static ?string $model = Administrasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $navigationLabel = 'Administrasi';

    protected static ?string $slug = 'master/administrasi';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewAdministrasi');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kd_adm')
                    ->label('Kode Administrasi')
                    ->numeric()
                    ->nullable(),
                TextInput::make('wilayah_adm')
                    ->label('Wilayah Administrasi')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('nm_adm')
                    ->label('Nama Administrasi')
                    ->maxLength(255)
                    ->nullable(),
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
                TextColumn::make('kd_adm')
                    ->label('Kode Administrasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('wilayah_adm')
                    ->label('Wilayah Administrasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nm_adm')
                    ->label('Nama Administrasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->can('editAdministrasi');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->can('deleteAdministrasi');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->can('deleteAdministrasi');
                        }),
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
            'index' => Pages\ListAdministrasis::route('/'),
            'create' => Pages\CreateAdministrasi::route('/create'),
            'edit' => Pages\EditAdministrasi::route('/{record}/edit'),
        ];
    }
}