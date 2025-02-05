<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Kain;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\TransaksiResource\Pages;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;
    protected static ?string $navigationGroup = 'Pengaturan Stok';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pergerakan Stok';
    public static ?string $label = 'Pergerakan Stok';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('kain_id')
                ->label('Nama Kain')
                ->relationship(name: 'kain', titleAttribute: 'nama_kain')
                ->searchable()
                ->required(),

            TextInput::make('jenis_transaksi')
                ->default('keluar')
                ->disabled()
                ->hidden()
                ->dehydrated()
                ->afterStateHydrated(function (callable $set) {
                    $set('jenis_transaksi', 'keluar'); // Override state here
                }),

            TextInput::make('jumlah')
                ->label('Jumlah Stok Keluar (Yard)')
                ->numeric()
                ->required(),

            Textarea::make('keterangan')
                ->label('Keterangan')
                ->columnSpanFull(),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kain.nama_kain')
                    ->label('Nama Kain')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jenis_transaksi')
                    ->label('Jenis Transaksi')
                    ->badge()
                    ->alignment(Alignment::Center)
                    ->color(fn(string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->formatStateUsing(function ($record) {
                        return $record->created_at->format('Y-m-d');
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(15),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make()
                    ->hidden(fn ($record) => $record->jenis_transaksi === 'masuk'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Export')
                        ->icon('heroicon-o-printer')
                        ->label('Print PDF')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                echo PDF::loadHTML(
                                    Blade::render('transaksi.print', ['records' => $records])
                                )->stream();
                            }, 'laporan-pergerakan-stok.pdf');
                        }),

                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
