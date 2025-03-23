<?php

namespace App\Filament\Resources;

use App\Enums\AdStatusEnum;
use App\Filament\Resources\AdResource\Pages;
use App\Filament\Resources\AdResource\RelationManagers;
use App\Models\Ad;
use App\Models\Category;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;


class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'المنتجات';
    protected static ?string $modelLabel = 'منتج';
    protected static ?string $pluralModelLabel = 'المنتجات';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('التصنيف')
                    ->options(Category::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(150),

                TextInput::make('sub_title')
                    ->label('العنوان الفرعي')
                    ->maxLength(150),

                TextInput::make('slug')
                    ->label('الاسم بالرابط')
                    ->required()
                    ->maxLength(170)
                    ->unique(ignoreRecord: true),

                FileUpload::make('thumbnail_path')
                    ->label('صورة العرض')
                    ->image()
                    ->directory('ads-thumbnails')
                    ->disk('public'),

                TextInput::make('price')
                    ->label('السعر')
                    ->numeric()
                    ->required(),

                TextInput::make('quantity_available')
                    ->label('الكمية المتاحة')
                    ->numeric()
                    ->default(1),


                TextInput::make('weight')
                    ->label('الوزن')
                    ->numeric()
                    ->nullable(),

                MarkdownEditor::make('description')
                    ->label('الوصف')
                    ->required(),

                Radio::make('status')
                    ->label('الحالة')
                    ->options([
                        AdStatusEnum::DRAFT->value     => AdStatusEnum::DRAFT->label(),
                        AdStatusEnum::PENDING->value   => AdStatusEnum::PENDING->label(),
                        AdStatusEnum::AVAILABLE->value => AdStatusEnum::AVAILABLE->label(),
                        AdStatusEnum::SOLD_OUT->value  => AdStatusEnum::SOLD_OUT->label(),
                    ])
                    ->default(AdStatusEnum::DRAFT->value)
                    ->required(),

                DateTimePicker::make('approved_at')
                    ->label('تاريخ الاعتماد'),

                DateTimePicker::make('expires_at')
                    ->label('تاريخ الانتهاء'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_path')
                    ->label('الصورة')
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->thumbnail_path)),

                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('السعر')
                    ->money('SAR'), // عدل العملة حسب عملتك

                TextColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(fn ($state) => AdStatusEnum::from($state)->label())
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        AdStatusEnum::DRAFT->value     => 'gray',
                        AdStatusEnum::PENDING->value   => 'warning',
                        AdStatusEnum::AVAILABLE->value => 'success',
                        AdStatusEnum::SOLD_OUT->value  => 'danger',
                        default                        => 'gray',
                    })
                    ->sortable(),


                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAd::route('/create'),
            'edit' => Pages\EditAd::route('/{record}/edit'),
        ];
    }
}
