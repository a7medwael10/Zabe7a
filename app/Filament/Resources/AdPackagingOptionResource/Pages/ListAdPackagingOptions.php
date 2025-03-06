<?php

namespace App\Filament\Resources\AdPackagingOptionResource\Pages;

use App\Filament\Resources\AdPackagingOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdPackagingOptions extends ListRecords
{
    protected static string $resource = AdPackagingOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
