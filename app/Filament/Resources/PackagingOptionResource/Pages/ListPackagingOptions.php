<?php

namespace App\Filament\Resources\PackagingOptionResource\Pages;

use App\Filament\Resources\PackagingOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackagingOptions extends ListRecords
{
    protected static string $resource = PackagingOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
