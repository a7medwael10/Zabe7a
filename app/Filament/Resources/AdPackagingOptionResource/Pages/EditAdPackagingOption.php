<?php

namespace App\Filament\Resources\AdPackagingOptionResource\Pages;

use App\Filament\Resources\AdPackagingOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdPackagingOption extends EditRecord
{
    protected static string $resource = AdPackagingOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
