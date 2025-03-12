<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;
    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'الشرائح';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Forms\Components\TextInput::make('title')
                    ->label('العنوان')
                    ->maxLength(100)
                    ->nullable(),

                Forms\Components\TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0),




                FileUpload::make('image_path')
                    ->label('مسار الصورة')
                    ->disk('public')
                    ->directory('sliders')
                    ->image()
                    ->required(),

                Textarea::make('description')
                    ->label('الوصف')
                    ->nullable(),

                Select::make('sliderable_type')
                    ->label('النوع المرتبط')
                    ->options([
                        Category::class => 'تصنيف',
                        Offer::class => 'عرض',
                        Ad::class => 'منتج',
                    ])
                    ->required()
                    ->reactive(),

                Select::make('sliderable_id')
                    ->label('العنصر المرتبط')
                    ->options(function (callable $get) {
                        $type = $get('sliderable_type');
                        return match ($type) {
                            Category::class => Category::pluck('name', 'id'),
                            Offer::class => Offer::pluck('title', 'id'),
                            Ad::class => Ad::pluck('title', 'id'),
                            default => [],
                        };
                    }),

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
                    ->label('الصورة')
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->image_path)),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable(),
                TextColumn::make('sliderable_type')
                    ->label('النوع المرتبط')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        \App\Models\Category::class => 'تصنيف',
                        \App\Models\Offer::class => 'عرض',
                        \App\Models\Ad::class => 'منتج',
                        default => 'غير معروف',
                    }),
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
