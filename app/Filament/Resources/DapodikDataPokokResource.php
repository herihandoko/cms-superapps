<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DapodikDataPokokResource\Pages;
use App\Models\DapodikDataPokok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DapodikDataPokokResource extends Resource
{
    protected static ?string $model = DapodikDataPokok::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Dapodik';

    protected static ?string $navigationLabel = 'Data Pokok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_satuan_pendidikan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('npsn')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bentuk_pendidikan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status_sekolah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(500),
                Forms\Components\TextInput::make('desa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kecamatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kabupaten_kota')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lintang')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bujur')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tmt_akreditasi'),
                Forms\Components\TextInput::make('akreditasi')
                    ->maxLength(3),
                Forms\Components\TextInput::make('rombel_t1')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t2')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t3')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t4')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t5')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t6')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t7')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t8')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t9')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t10')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t11')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t12')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_t13')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_tka')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_tkb')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_pkta')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_pktb')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rombel_pktc')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('jumlah_rombel')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('peserta_didik_baru')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tka_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tka_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tkb_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tkb_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t1_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t1_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t2_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t2_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t3_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t3_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t4_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t4_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t5_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t5_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t6_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t6_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t7_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t7_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t8_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t8_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t9_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t9_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t10_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t10_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t11_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t11_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t12_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t12_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t13_l')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('t13_p')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('jumlah_ruang_kelas')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('guru')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('tendik')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_satuan_pendidikan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('npsn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bentuk_pendidikan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kabupaten_kota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_rombel')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('peserta_didik_baru')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tendik')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDapodikDataPokok::route('/'),
            'create' => Pages\CreateDapodikDataPokok::route('/create'),
            'edit' => Pages\EditDapodikDataPokok::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
} 