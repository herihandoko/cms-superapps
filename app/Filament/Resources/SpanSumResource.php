<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpanSumResource\Pages;
use App\Filament\Resources\SpanSumResource\RelationManagers;
use App\Models\SpanSum;
use App\Models\MasterOpd; // Added for relation
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter; // Added for select filter
use Filament\Tables\Filters\Indicator; // Added for indicator
use Filament\Tables\Filters\SelectFilter; // Added for select filter
use Filament\Tables\Filters\TernaryFilter; // Added for ternary filter
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model; // Added for type hinting
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use Illuminate\Contracts\Auth\Authenticatable; // Replaced with App\Models\User
use App\Models\User; // Added for permission checks
use Illuminate\Support\Facades\Auth; // Added for permission checks

class SpanSumResource extends Resource
{
    protected static ?string $model = SpanSum::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square'; // Changed icon

    protected static ?string $navigationGroup = 'Span Lapor'; // Added navigation group

    protected static ?string $navigationLabel = 'Summary'; // Added navigation label

    protected static ?int $navigationSort = 1; // Added navigation sort

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kode_opd')
                    ->label('OPD')
                    ->relationship('masterOpd', 'nama_opd') // Assuming 'nama_opd' is the display column in MasterOpd
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('tgl_aduan')
                    ->label('Tanggal Aduan')
                    ->required(),
                TextInput::make('belum_terverifikasi')
                    ->label('Belum Terverifikasi')
                    ->numeric()
                    ->default(0),
                TextInput::make('belum_ditindaklanjuti')
                    ->label('Belum Ditindaklanjuti')
                    ->numeric()
                    ->default(0),
                TextInput::make('proses')
                    ->label('Proses')
                    ->numeric()
                    ->default(0),
                TextInput::make('selesai')
                    ->label('Selesai')
                    ->numeric()
                    ->default(0),
                TextInput::make('total_aduan')
                    ->label('Total Aduan')
                    ->numeric()
                    ->default(0),
                TextInput::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('masterOpd.nama_opd') // Display related OPD name
                    ->label('OPD')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tgl_aduan')
                    ->label('Tanggal Aduan')
                    ->date()
                    ->sortable(),
                TextColumn::make('belum_terverifikasi')
                    ->label('Belum Verifikasi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('belum_ditindaklanjuti')
                    ->label('Belum Tindaklanjut')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('proses')
                    ->label('Proses')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('selesai')
                    ->label('Selesai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_aduan')
                    ->label('Total Aduan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('create_at') // Using DDL column name
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('update_at') // Using DDL column name
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('tgl_aduan')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $q) => $q->whereDate('tgl_aduan', '>=', $data['created_from'])
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $q) => $q->whereDate('tgl_aduan', '<=', $data['created_until'])
                            );
                    }),
                SelectFilter::make('masterOpd') // Used FQCN for consistency
                    ->label('OPD')
                    ->relationship('masterOpd', 'nama_opd') // Assumes 'nama_opd' is the display column in MasterOpd
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // Added ViewAction
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
            'index' => Pages\ListSpanSums::route('/'),
            'create' => Pages\CreateSpanSum::route('/create'),
            'view' => Pages\ViewSpanSum::route('/{record}'), // Added view route
            'edit' => Pages\EditSpanSum::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSpanSummary');
    }

    public static function canView(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSpanSummary');
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('addSpanSummary');
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('editSpanSummary');
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSpanSummary');
    }

    public static function canDeleteAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSpanSummary');
    }

    // Add other permission checks if needed, like canForceDelete, canReplicate, etc.
    // For simplicity, we'll assume the basic CRUD permissions cover most cases.
    // If you use soft deletes and want to control restore, add canRestore and canRestoreAny.
    // If you use force delete, add canForceDelete and canForceDeleteAny.
}
