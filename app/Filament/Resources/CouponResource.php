<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'الكوبونات';
    protected static ?string $pluralModelLabel = 'الكوبونات';
    protected static ?string $modelLabel = 'كوبون';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('كود الكوبون')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),

                Select::make('type')
                    ->label('نوع الخصم')
                    ->required()
                    ->options([
                        'percentage' => 'نسبة مئوية',
                        'fixed' => 'قيمة ثابتة',
                    ]),

                TextInput::make('value')
                    ->label('قيمة الخصم')
                    ->numeric()
                    ->required(),

                TextInput::make('minimum_order_amount')
                    ->label('الحد الأدنى للطلب')
                    ->numeric(),

                TextInput::make('max_usage_per_user')
                    ->label('الحد الأقصى للاستخدام لكل مستخدم')
                    ->numeric()
                    ->default(1),

                TextInput::make('total_usage_limit')
                    ->label('الحد الأقصى للاستخدام الكلي')
                    ->numeric(),

                DateTimePicker::make('valid_from')
                    ->label('صالح من')
                    ->required(),

                DateTimePicker::make('valid_to')
                    ->label('صالح حتى')
                    ->required(),

                Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('كود الكوبون')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('نوع الخصم')
                    ->formatStateUsing(fn ($state) => $state === 'percentage' ? 'نسبة مئوية' : 'قيمة ثابتة'),

                TextColumn::make('value')
                    ->label('قيمة الخصم'),

                TextColumn::make('minimum_order_amount')
                    ->label('الحد الأدنى للطلب'),

                TextColumn::make('max_usage_per_user')
                    ->label('الاستخدام لكل مستخدم'),

                TextColumn::make('total_usage_limit')
                    ->label('الاستخدام الكلي'),

                TextColumn::make('used_count')
                    ->label('عدد مرات الاستخدام'),

                BooleanColumn::make('is_active')
                    ->label('نشط'),

                TextColumn::make('valid_from')
                    ->label('صالح من')
                    ->dateTime('Y-m-d H:i'),

                TextColumn::make('valid_to')
                    ->label('صالح حتى')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف متعدد'),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
