<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiskominfoOpTopTenSpanResource\Pages;
use App\Models\DiskominfoOpTopTenSpan;
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

class DiskominfoOpTopTenSpanResource extends Resource
{
    protected static ?string $model = DiskominfoOpTopTenSpan::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Span Lapor'; // Matches existing Span Lapor group

    protected static ?string $navigationLabel = 'Top Ten Span';

    protected static ?string $slug = 'span-lapor/top-ten-span';

    // Assuming this comes after 'Summary' in the 'Span Lapor' group
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')
                    ->label('ID')
                    ->integer()
                    ->required()
                    ->disabledOn('edit'), // Assuming ID is not editable after creation
                TextInput::make('instansi')
                    ->label('Instansi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('periode')
                    ->label('Periode')
                    ->required()
                    ->maxLength(255),
                TextInput::make('kategori')
                    ->label('Kategori')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->required()
                    ->maxLength(255), // Kept as string input as per DDL
                TextInput::make('source')
                    ->label('Source')
                    ->required()
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
                TextColumn::make('instansi')
                    ->label('Instansi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
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
            'index' => Pages\ListDiskominfoOpTopTenSpans::route('/'),
            'create' => Pages\CreateDiskominfoOpTopTenSpan::route('/create'),
            'view' => Pages\ViewDiskominfoOpTopTenSpan::route('/{record}'),
            'edit' => Pages\EditDiskominfoOpTopTenSpan::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSpanTopTen');
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('addSpanTopTen');
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('editSpanTopTen');
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSpanTopTen');
    }

    public static function canDeleteAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSpanTopTen');
    }

    public static function canView(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSpanTopTen');
    }
}