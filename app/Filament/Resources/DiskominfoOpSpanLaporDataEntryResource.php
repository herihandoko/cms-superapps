<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiskominfoOpSpanLaporDataEntryResource\Pages;
use App\Models\DiskominfoPengaduanEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use App\Imports\SpanLaporDataEntryImport;
use Maatwebsite\Excel\Facades\Excel;

class DiskominfoOpSpanLaporDataEntryResource extends Resource
{
    protected static ?string $model = DiskominfoPengaduanEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Span Lapor';

    protected static ?string $navigationLabel = 'Report Entry';

    protected static ?string $slug = 'span-lapor/report-entry';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('unit_kerja')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('belum_terverifikasi')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('belum_ditindaklanjuti')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('proses')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('selesai')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('persentase_tl')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01),
                Forms\Components\TextInput::make('rtl')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\TextInput::make('rhp')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit_kerja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('belum_terverifikasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('belum_ditindaklanjuti')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selesai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('persentase_tl')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('rtl')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rhp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListDiskominfoOpSpanLaporDataEntries::route('/'),
            'create' => Pages\CreateDiskominfoOpSpanLaporDataEntry::route('/create'),
            'edit' => Pages\EditDiskominfoOpSpanLaporDataEntry::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSpanReportEntry');
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('addSpanReportEntry');
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('editSpanReportEntry');
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSpanReportEntry');
    }

    public static function canDeleteAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSpanReportEntry');
    }
} 