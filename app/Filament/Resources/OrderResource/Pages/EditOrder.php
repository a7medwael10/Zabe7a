<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // تحميل العلاقة orderItems في صفحة التعديل
        $this->record->loadMissing(['orderItems', 'user', 'deliveryCompany', 'address']);

        return $data;
    }
}
