<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Enums\OrderStatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'الطلبات';
    protected static ?string $pluralModelLabel = 'الطلبات';
    protected static ?string $modelLabel = 'طلب';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['orderItems', 'user', 'deliveryCompany', 'address']);
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('تفاصيل الطلب')
                ->schema([
                    TextInput::make('order_number')
                        ->label('رقم الطلب')
                        ->disabled(),

                    Select::make('user_id')
                        ->relationship('user', 'first_name')
                        ->label('العميل')
                        ->disabled(),

                    Select::make('delivery_company_id')
                        ->relationship('deliveryCompany', 'name')
                        ->label('شركة التوصيل')
                        ->disabled(),

//                    Select::make('address_id')
//                        ->relationship('address')
//                        ->label('عنوان التوصيل')
//                        ->disabled(),

                    TextInput::make('subtotal')
                        ->label('المجموع الفرعي')
                        ->disabled(),

                    TextInput::make('shipping_cost')
                        ->label('تكلفة الشحن')
                        ->disabled(),

                    TextInput::make('discount')
                        ->label('الخصم')
                        ->disabled(),

                    TextInput::make('total')
                        ->label('الإجمالي')
                        ->disabled(),

//                    Select::make('payment_method_id')
//                        ->relationship('paymentMethod', 'name')
//                        ->label('طريقة الدفع')
//                        ->disabled(),

                    Textarea::make('customer_notes')
                        ->label('ملاحظات العميل')
                        ->disabled(),
                ])->columns(2),

            Section::make('حالة الطلب')
                ->schema([
                    Select::make('status')
                        ->label('حالة الطلب')
                        ->options(
                            collect(OrderStatusEnum::cases())->mapWithKeys(fn ($status) => [
                                $status->value => $status->label()
                            ])
                        )
                        ->required(),
                ]),

            Section::make('المنتجات في الطلب')
                ->schema([
                    Repeater::make('orderItems')
                        ->label('المنتجات')
                        ->relationship('orderItems')
                        ->schema([
                            TextInput::make('title')->label('اسم المنتج')->disabled(),
                            TextInput::make('quantity')->label('الكمية')->disabled(),
                            TextInput::make('unit_price')->label('سعر الوحدة')->disabled(),
                            TextInput::make('total')->label('الإجمالي')->disabled(),
                            Textarea::make('notes')->label('ملاحظات')->disabled(),
                        ])
                        ->columns(2)
                        ->disableItemCreation()
                        ->disableItemDeletion()
                        ->disableItemMovement(),
                ]),
        ]);
    }

    /**
     * جدول عرض الطلبات
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('العميل'),

                Tables\Columns\TextColumn::make('total')
                    ->label('الإجمالي')
                    ->money('SAR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn ($state) => OrderStatusEnum::from($state)->label())
                    ->color(fn ($state) => match ($state) {
                        OrderStatusEnum::PENDING->value     => 'warning',
                        OrderStatusEnum::SLAUGHTERED->value => 'info',
                        OrderStatusEnum::PACKED->value      => 'info',
                        OrderStatusEnum::WAITING->value     => 'secondary',
                        OrderStatusEnum::ON_WAY->value      => 'primary',
                        OrderStatusEnum::DONE->value        => 'success',
                        default                             => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * صفحات الموارد
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    /**
     * منع إنشاء طلب جديد من اللوحة
     */
    public static function canCreate(): bool
    {
        return false;
    }
}
