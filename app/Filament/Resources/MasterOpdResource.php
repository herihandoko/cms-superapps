<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterOpdResource\Pages;
use App\Filament\Resources\MasterOpdResource\RelationManagers;
use App\Models\MasterOpd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model; // Added import for Model
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate; // Added import for Gate

class MasterOpdResource extends Resource
{
    protected static ?string $model = MasterOpd::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // Changed icon

    protected static ?string $navigationGroup = 'Master'; // Set navigation group

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_opd')
                    ->label('Kode OPD')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_opd')
                    ->label('Nama OPD')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_pimpinan')
                    ->label('Nama Pimpinan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip_pimpinan')
                    ->label('NIP Pimpinan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jabatan_pimpinan')
                    ->label('Jabatan Pimpinan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('golongan_pimpinan')
                    ->label('Golongan Pimpinan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pangkat_pimpinan')
                    ->label('Pangkat Pimpinan')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('foto_pimpinan')
                    ->label('Foto Pimpinan')
                    ->image()
                    ->directory('opd/foto_pimpinan')
                    ->visibility('public'), // Or 'private'
                Forms\Components\TextInput::make('domain_opd')
                    ->label('Domain OPD')
                    ->maxLength(255),
                Forms\Components\Textarea::make('alamat_opd')
                    ->label('Alamat OPD')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('email_opd')
                    ->label('Email OPD')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_kontak_opd')
                    ->label('No Kontak OPD')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('berlaku_mulai')
                    ->label('Berlaku Mulai'),
                Forms\Components\DatePicker::make('berlaku_selesai')
                    ->label('Berlaku Selesai'),
                Forms\Components\Toggle::make('flag_aktif')
                    ->label('Aktif')
                    ->default(true),
                Forms\Components\Select::make('jenis_opd')
                    ->label('Jenis OPD')
                    ->options([
                        'dinas' => 'Dinas',
                        'badan' => 'Badan',
                        'upt' => 'UPT',
                    ]),
                Forms\Components\FileUpload::make('icon_opd')
                    ->label('Icon OPD')
                    ->image()
                    ->directory('opd/icon_opd')
                    ->visibility('public'), // Or 'private'
                Forms\Components\Toggle::make('flag_cdn_source')
                    ->label('Gunakan CDN Source')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_opd')
                    ->label('Kode OPD')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_opd')
                    ->label('Nama OPD')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pimpinan')
                    ->label('Nama Pimpinan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_opd')
                    ->label('Jenis OPD')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('flag_aktif')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMasterOpds::route('/'),
            'create' => Pages\CreateMasterOpd::route('/create'),
            'view'   => Pages\ViewMasterOpd::route('/{record}'),
            'edit' => Pages\EditMasterOpd::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Add permission checks
    public static function canViewAny(): bool
    {
        return Gate::allows('viewMasterOpd');
    }

    public static function canView(Model $record): bool
    {
        return Gate::allows('viewMasterOpd', $record);
    }

    public static function canCreate(): bool
    {
        return Gate::allows('addMasterOpd');
    }

    public static function canEdit(Model $record): bool
    {
        return Gate::allows('editMasterOpd', $record);
    }

    public static function canDelete(Model $record): bool
    {
        return Gate::allows('deleteMasterOpd', $record);
    }
}
