<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Kain;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KainResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KainResource\RelationManagers;

class KainResource extends Resource
{
    protected static ?string $model = Kain::class;

    protected static ?string $navigationGroup = 'Pengaturan Stok';
    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';
    protected static ?string $navigationLabel = 'Kain';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kain')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('stok')
                    ->label('Stok per roll')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('panjang_per_roll')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('nama_kain')
                ->searchable(),
            Tables\Columns\TextColumn::make('stok')
                ->label('Stok Dalam Roll')
                ->numeric()
                ->alignment(Alignment::Center)
                ->sortable(),
            Tables\Columns\TextColumn::make('panjang_per_roll')
                ->label('Stok Per Roll')
                ->alignment(Alignment::Center)
                ->numeric()
                ->sortable()
                ->formatStateUsing(function ($record) {
                    return rtrim(rtrim($record->panjang_per_roll, '0'), '.').' yard';
                }),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('transaksis')
                ->label('Stok Tersedia')
                ->sortable()
                ->alignment(Alignment::Center)
                ->formatStateUsing(function ($record) {
                $stokAwalYard = $record->stok * $record->panjang_per_roll;
                $stokKeluar = $record->transaksis->where('jenis_transaksi', 'keluar')->sum('jumlah');
                return ($stokAwalYard - $stokKeluar) . ' yard';
                if ($stokKeluar === 0) {
                    return $stokAwalYard;
                }}),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKains::route('/'),
            'create' => Pages\CreateKain::route('/create'),
            'edit' => Pages\EditKain::route('/{record}/edit'),
        ];
    }
}
