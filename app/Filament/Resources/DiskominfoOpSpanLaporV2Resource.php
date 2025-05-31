<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiskominfoOpSpanLaporV2Resource\Pages;
use App\Models\DiskominfoOpSpanLaporV2;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiskominfoOpSpanLaporV2Resource extends Resource
{
    protected static ?string $model = DiskominfoOpSpanLaporV2::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Span Lapor';
    
    protected static ?string $navigationLabel = 'Lapor Harian';
    
    protected static ?string $modelLabel = 'Lapor Harian';
    
    protected static ?string $pluralModelLabel = 'Lapor Harian';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('tracking_id')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        Forms\Components\DatePicker::make('tanggal_laporan')
                            ->required(),
                        Forms\Components\TimePicker::make('waktu_laporan')
                            ->required()
                            ->seconds(false),
                        Forms\Components\TextInput::make('nama_pelapor')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('klasifikasi_laporan')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('id_kategori')
                            ->numeric(),
                        Forms\Components\TextInput::make('kategori')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('judul_laporan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('isi_laporan_awal')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('isi_laporan_akhir')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('tipe_laporan')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('sumber_laporan')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('instansi_induk')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('id_instansi_terdisposisi')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('instansi_terdisposisi')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('status_laporan')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('alasan_tunda_arsip')
                            ->rows(3),
                        Forms\Components\TextInput::make('kd_provinsi')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('kd_kabupaten_kota')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('kd_kecamatan')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('kd_desa_kelurahan')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('source')
                            ->maxLength(255),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_laporan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_laporan')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pelapor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('klasifikasi_laporan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul_laporan')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('tipe_laporan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_laporan')
                    ->searchable(),
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
            'index' => Pages\ListDiskominfoOpSpanLaporV2s::route('/'),
            'create' => Pages\CreateDiskominfoOpSpanLaporV2::route('/create'),
            'edit' => Pages\EditDiskominfoOpSpanLaporV2::route('/{record}/edit'),
        ];
    }
} 