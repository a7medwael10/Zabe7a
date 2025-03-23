<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryCompanyResource\Pages;
use App\Filament\Resources\DeliveryCompanyResource\RelationManagers;
use App\Models\DeliveryCompany;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
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

class DeliveryCompanyResource extends Resource
{
    protected static ?string $model = DeliveryCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'شركات التوصيل';
    protected static ?string $pluralModelLabel = 'شركات التوصيل';
    protected static ?string $modelLabel = 'شركة توصيل';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('اسم الشركة')
                    ->required()
                    ->maxLength(100),

                FileUpload::make('logo')
                    ->label('شعار الشركة')
                    ->image()
                    ->disk('public')
                    ->directory('delivery-companies-logos'),

                Textarea::make('description')
                    ->label('وصف الشركة')
                    ->maxLength(500),

                TextInput::make('base_price')
                    ->label('سعر التوصيل الأساسي')
                    ->numeric()
                    ->required(),

                TextInput::make('price_per_km')
                    ->label('سعر التوصيل لكل كيلومتر')
                    ->numeric()
                    ->default(0.00),

                TextInput::make('estimated_delivery_days')
                    ->label('أيام التوصيل المقدرة')
                    ->numeric()
                    ->required(),

                Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الشركة')
                    ->searchable(),

                TextColumn::make('base_price')
                    ->label('السعر الأساسي')
                    ->money('SAR'),

                TextColumn::make('price_per_km')
                    ->label('سعر لكل كيلومتر')
                    ->money('SAR'),

                TextColumn::make('estimated_delivery_days')
                    ->label('أيام التوصيل المقدرة'),

                BooleanColumn::make('is_active')
                    ->label('نشطة'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
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
            'index' => Pages\ListDeliveryCompanies::route('/'),
            'create' => Pages\CreateDeliveryCompany::route('/create'),
            'edit' => Pages\EditDeliveryCompany::route('/{record}/edit'),
        ];
    }
}
