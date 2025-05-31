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
                Tables\Filters\Filter::make('tracking_id')
                    ->form([
                        Forms\Components\TextInput::make('tracking_id')
                            ->label('Tracking ID')
                            ->placeholder('Cari Tracking ID'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tracking_id'],
                            fn (Builder $query, $trackingId): Builder => $query->where('tracking_id', 'like', "%{$trackingId}%"),
                        );
                    }),
                Tables\Filters\Filter::make('tanggal_laporan')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_laporan', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_laporan', '<=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('waktu_laporan')
                    ->form([
                        Forms\Components\TimePicker::make('waktu_dari')
                            ->label('Dari Jam')
                            ->seconds(false),
                        Forms\Components\TimePicker::make('waktu_sampai')
                            ->label('Sampai Jam')
                            ->seconds(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['waktu_dari'],
                                fn (Builder $query, $time): Builder => $query->whereTime('waktu_laporan', '>=', $time),
                            )
                            ->when(
                                $data['waktu_sampai'],
                                fn (Builder $query, $time): Builder => $query->whereTime('waktu_laporan', '<=', $time),
                            );
                    }),
                Tables\Filters\Filter::make('nama_pelapor')
                    ->form([
                        Forms\Components\TextInput::make('nama_pelapor')
                            ->label('Nama Pelapor')
                            ->placeholder('Cari Nama Pelapor'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nama_pelapor'],
                            fn (Builder $query, $nama): Builder => $query->where('nama_pelapor', 'like', "%{$nama}%"),
                        );
                    }),
                Tables\Filters\SelectFilter::make('status_laporan')
                    ->label('Status Laporan')
                    ->options(fn (): array => DiskominfoOpSpanLaporV2::distinct()->pluck('status_laporan', 'status_laporan')->toArray())
                    ->multiple()
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