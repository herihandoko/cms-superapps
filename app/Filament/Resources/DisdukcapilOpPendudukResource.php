<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisdukcapilOpPendudukResource\Pages;
use App\Models\DisdukcapilOpPenduduk;
use App\Models\MasterAdministrasi;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DisdukcapilOpPendudukResource extends Resource
{
    protected static ?string $model = DisdukcapilOpPenduduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Dukcapil';

    protected static ?string $navigationLabel = 'Summary Penduduk';

    protected static ?string $slug = 'dukcapil/summary-penduduk';

    // Assuming this comes after 'Master Administrasi' in the 'Dukcapil' group
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('waktu')
                    ->label('WAKTU')
                    ->required(),
                Select::make('kd_adm3')
                    ->label('PROVINSI/KAB/KOTA')
                    ->relationship('masterAdministrasi', 'nm_adm')
                    ->options(MasterAdministrasi::pluck('nm_adm', 'kd_adm'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('jlh_pddk')
                    ->label('JUMLAH PENDUDUK')
                    ->numeric(),
                TextInput::make('tgt_wktpd')
                    ->label('TARGET WKTP')
                    ->numeric(),
                TextInput::make('rek_wktpd')
                    ->label('WKTP TELAH REKAM')
                    ->numeric(),
                TextInput::make('brek_wktpd')
                    ->label('WKTP BELUM REKAM')
                    ->numeric(),
                TextInput::make('prr')
                    ->label('PRR')
                    ->numeric(),
                TextInput::make('kep_ktp_el')
                    ->label('KEPEMILIKAN KTP-EL')
                    ->numeric(),
                TextInput::make('aktv_ikd')
                    ->label('AKTIVASI IKD')
                    ->numeric(),
                TextInput::make('sisa_blktp')
                    ->label('SISA BLANGKO KTP-EL')
                    ->numeric(),
                TextInput::make('tgt_kia')
                    ->label('TARGET KIA')
                    ->numeric(),
                TextInput::make('mmlk_kia')
                    ->label('MEMILIKI KIA')
                    ->numeric(),
                TextInput::make('tgt_akt_lhr_0018')
                    ->label('TARGET AKTA KELAHIRAN 0-18 TAHUN')
                    ->numeric(),
                TextInput::make('mmlk_akt_lhr_0018')
                    ->label('MEMILIKI AKTA KELAHIRAN 0-18 TAHUN')
                    ->numeric(),
                TextInput::make('source')
                    ->label('Source')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('waktu')
                    ->label('WAKTU')
                    ->date()
                    ->sortable(),
                TextColumn::make('masterAdministrasi.nm_adm')
                    ->label('PROVINSI/KAB/KOTA')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jlh_pddk')
                    ->label('JUMLAH PENDUDUK')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tgt_wktpd')
                    ->label('TARGET WKTP')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rek_wktpd')
                    ->label('WKTP TELAH REKAM')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('brek_wktpd')
                    ->label('WKTP BELUM REKAM')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('prr')
                    ->label('PRR')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kep_ktp_el')
                    ->label('KEPEMILIKAN KTP-EL')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('aktv_ikd')
                    ->label('AKTIVASI IKD')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sisa_blktp')
                    ->label('SISA BLANGKO KTP-EL')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tgt_kia')
                    ->label('TARGET KIA')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mmlk_kia')
                    ->label('MEMILIKI KIA')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tgt_akt_lhr_0018')
                    ->label('TARGET AKTA KELAHIRAN 0-18 TAHUN')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mmlk_akt_lhr_0018')
                    ->label('MEMILIKI AKTA KELAHIRAN 0-18 TAHUN')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListDisdukcapilOpPenduduks::route('/'),
            'create' => Pages\CreateDisdukcapilOpPenduduk::route('/create'),
            'view' => Pages\ViewDisdukcapilOpPenduduk::route('/{record}'),
            'edit' => Pages\EditDisdukcapilOpPenduduk::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSummaryDukcapil');
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('addSummaryDukcapil');
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('editSummaryDukcapil');
    }

    public static function canDelete(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSummaryDukcapil');
    }

    public static function canDeleteAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('deleteSummaryDukcapil');
    }

    public static function canView(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->can('viewSummaryDukcapil');
    }
}