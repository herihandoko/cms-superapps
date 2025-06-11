<?php

namespace App\Filament\Resources\DiskominfoOpSpanLaporDataEntryResource\Pages;

use App\Filament\Resources\DiskominfoOpSpanLaporDataEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Imports\SpanLaporDataEntryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Filament\Notifications\Notification;

class ListDiskominfoOpSpanLaporDataEntries extends ListRecords
{
    protected static string $resource = DiskominfoOpSpanLaporDataEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->requiresConfirmation()
                ->modalDescription('Data yang ada sebelumnya akan dihapus dan diganti dengan data baru dari file Excel. Apakah Anda yakin ingin melanjutkan?')
                ->modalSubmitActionLabel('Ya, Import Data')
                ->modalCancelActionLabel('Batal')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('File Excel')
                        ->disk('temporary')
                        ->directory('excel-imports')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->required()
                ])
                ->action(function (array $data): void {
                    try {
                        $import = new SpanLaporDataEntryImport();
                        
                        // Get the full path of the uploaded file
                        $filePath = Storage::disk('temporary')->path($data['file']);
                        
                        Excel::import($import, $filePath);
                        
                        // Clean up: delete the temporary file
                        Storage::disk('temporary')->delete($data['file']);
                        
                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data berhasil diimport')
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        // Log the error for debugging
                        logger()->error('Import Error: ' . $e->getMessage(), [
                            'file' => $data['file'],
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        
                        // Clean up on error
                        if (isset($data['file'])) {
                            Storage::disk('temporary')->delete($data['file']);
                        }
                        
                        Notification::make()
                            ->title('Error')
                            ->body('Gagal import data: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
} 