<?php

namespace App\Filament\Resources\DapodikDataPokokResource\Pages;

use App\Filament\Resources\DapodikDataPokokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Imports\DapodikDataPokokImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class ListDapodikDataPokok extends ListRecords
{
    protected static string $resource = DapodikDataPokokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    \Filament\Forms\Components\View::make('filament.forms.components.warning')
                        ->viewData([
                            'message' => 'Proses import akan menghapus semua data yang ada dan menggantinya dengan data dari file Excel yang diupload.'
                        ]),
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('File Excel')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->disk('local')
                        ->directory('excel-imports')
                ])
                ->action(function (array $data) {
                    try {
                        file_put_contents(storage_path('logs/dapodik_action.log'), 'Sebelum import: ' . json_encode($data) . "\n", FILE_APPEND);
                        $path = Storage::disk('local')->path($data['file']);
                        Excel::import(new DapodikDataPokokImport, $path);
                        file_put_contents(storage_path('logs/dapodik_action.log'), 'Setelah import\n', FILE_APPEND);
                        // Delete the file after import
                        Storage::disk('local')->delete($data['file']);
                        
                        Notification::make()
                            ->title('Data berhasil diimport')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        file_put_contents(storage_path('logs/dapodik_action.log'), 'Error import: ' . $e->getMessage() . "\n", FILE_APPEND);
                        Notification::make()
                            ->title('Gagal mengimport data')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
} 