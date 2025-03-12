<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Models\Category;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag'; // ايقونة العروض
    protected static ?string $navigationLabel = 'العروض';
    protected static ?string $navigationGroup = 'إدارة العروض';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('عنوان العرض')
                    ->required()
                    ->maxLength(150),

                TextInput::make('sub_title')
                    ->label('العنوان الفرعي')
                    ->maxLength(150),

                Select::make('category_id')
                    ->label('التصنيف')
                    ->options(Category::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(170)
                    ->unique(ignoreRecord: true),


                TextInput::make('original_price')
                    ->label('السعر الأصلي')
                    ->required()
                    ->numeric()
                    ->reactive() // عشان تشتغل مع afterStateUpdated
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $discount = $get('discount_percentage') ?? 0;
                        $offerPrice = $state - ($state * $discount / 100);
                        $set('offer_price', round($offerPrice, 2));
                    }),

                TextInput::make('discount_percentage')
                    ->label('نسبة الخصم')
                    ->numeric()
                    ->reactive() // نفس القصة
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $originalPrice = $get('original_price') ?? 0;
                        $offerPrice = $originalPrice - ($originalPrice * $state / 100);
                        $set('offer_price', round($offerPrice, 2));
                    }),

                TextInput::make('offer_price')
                    ->label('السعر بعد الخصم')
                    ->disabled() // عشان ما يعدلوش عليه يدوي
                    ->dehydrated(), // عشان يتبعت ويخزن في الداتا بيز



                TextInput::make('gift')
                    ->label('هدية العرض')
                    ->nullable(),


                Forms\Components\DateTimePicker::make('starts_at')
                    ->label('بداية العرض')
                    ->nullable(),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('نهاية العرض')
                    ->nullable(),


                MarkdownEditor::make('description')
                    ->label('وصف العرض')
                    ->nullable(),
                FileUpload::make('thumbnail_path')
                    ->label('صورة العرض')
                    ->image()
                    ->directory('offers')
                    ->disk('public')
                    ->nullable(),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_path')
                    ->label('الصورة')
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->thumbnail_path)),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),

                Tables\Columns\TextColumn::make('original_price')
                    ->label('السعر الأصلي')
                    ->money('SAR'),

                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('نسبة الخصم')
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('offer_price')
                    ->label('السعر بعد الخصم')
                    ->money('SAR'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('بداية العرض')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('نهاية العرض')
                    ->dateTime(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit'   => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
