<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomoditasResource\Pages;
use App\Filament\Resources\KomoditasResource\RelationManagers;
use App\Models\Komoditas;
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

class KomoditasResource extends Resource
{
    protected static ?string $model = Komoditas::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Master';

    protected static ?string $navigationLabel = 'Komoditas';

    protected static ?string $slug = 'master/komoditas';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewKomoditas');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_kmd')
                    ->label('ID Komoditas')
                    ->numeric()
                    ->required()
                    ->disabledOn('edit') // Assuming ID should not be changed after creation
                    ->unique(Komoditas::class, 'id_kmd', ignoreRecord: true),
                TextInput::make('nm_kmd')
                    ->label('Nama Komoditas') // Changed label
                    ->required()
                    ->maxLength(255),
                TextInput::make('hpp_het') // Uses the accessor/mutator for 'hpp/het'
                    ->label('HPP/HET')
                    ->maxLength(255),
                TextInput::make('source')
                    ->label('Source')
                    ->maxLength(255),
                // TextInput::make('satuan') // Removed
                //    ->label('Satuan')
                //    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kmd')
                    ->label('ID Komoditas')
                    ->sortable(),
                TextColumn::make('nm_kmd') // Changed from nama_pangan
                    ->label('Nama Komoditas') // Changed label
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hpp_het') // Uses the accessor for 'hpp/het'
                    ->label('HPP/HET')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->sortable(),
                // TextColumn::make('satuan') // Removed
                //    ->label('Satuan')
                //    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('update_at') // Eloquent handles the 'update_at' column name via const UPDATED_AT
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
                        return $user && $user->can('editKomoditas');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->can('deleteKomoditas');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->can('deleteKomoditas');
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
            'index' => Pages\ListKomoditas::route('/'),
            'create' => Pages\CreateKomoditas::route('/create'),
            'edit' => Pages\EditKomoditas::route('/{record}/edit'),
        ];
    }
}