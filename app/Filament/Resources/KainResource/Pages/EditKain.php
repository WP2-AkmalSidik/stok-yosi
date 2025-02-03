<?php

namespace App\Filament\Resources\KainResource\Pages;

use App\Filament\Resources\KainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKain extends EditRecord
{
    protected static string $resource = KainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
