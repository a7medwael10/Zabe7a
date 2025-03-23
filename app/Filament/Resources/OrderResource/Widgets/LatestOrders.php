<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\OrderStatusEnum;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'أحدث الطلبات';
    protected int | string | array $columnSpan = 'full';


    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->latest()
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('order_number')
                ->label('رقم الطلب')
                ->searchable()
                ->sortable(),

            TextColumn::make('user.first_name')
                ->label('العميل')
                ->searchable(),

            TextColumn::make('status')
                ->label('الحالة')
                ->formatStateUsing(fn ($state) => OrderStatusEnum::from($state)?->label() ?? 'غير معروف')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    OrderStatusEnum::PENDING->value     => 'warning',
                    OrderStatusEnum::SLAUGHTERED->value => 'info',
                    OrderStatusEnum::PACKED->value      => 'info',
                    OrderStatusEnum::WAITING->value     => 'secondary',
                    OrderStatusEnum::ON_WAY->value      => 'primary',
                    OrderStatusEnum::DONE->value        => 'success',
                    default                             => 'gray',
                }),

            TextColumn::make('total')
                ->label('السعر الكلي')
                ->money('SAR'),

            TextColumn::make('created_at')
                ->label('تاريخ الإنشاء')
                ->dateTime('Y-m-d H:i'),
        ];
    }


    protected static ?int $sort = 2; // ترتيب الويدجيت في الصفحة
}
