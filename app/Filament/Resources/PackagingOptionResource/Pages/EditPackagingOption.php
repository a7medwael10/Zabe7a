<?php

namespace App\Filament\Resources\PackagingOptionResource\Pages;

use App\Filament\Resources\PackagingOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackagingOption extends EditRecord
{
    protected static string $resource = PackagingOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
