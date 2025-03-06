<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;
    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'الشرائح';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('مسار الصورة')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('العنوان')
                    ->maxLength(100)
                    ->nullable(),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->nullable(),
                Forms\Components\Select::make('target_type')
                    ->label('نوع الهدف')
                    ->options([
                        'category' => 'فئة',
                        'ad' => 'إعلان',
                        'external_url' => 'رابط خارجي',
                        'none' => 'لا شيء',
                    ])
                    ->nullable(),
                Forms\Components\TextInput::make('target_id')
                    ->label('معرف الهدف')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('target_url')
                    ->label('رابط الهدف')
                    ->url()
                    ->nullable(),
                Forms\Components\TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('الصورة'),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_type')
                    ->label('نوع الهدف'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
