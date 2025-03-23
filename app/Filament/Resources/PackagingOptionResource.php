<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackagingOptionResource\Pages;
use App\Filament\Resources\PackagingOptionResource\RelationManagers;
use App\Models\PackagingOption;
use Filament\Forms;
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

class PackagingOptionResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'خيارات التقطيع والتغليف';
    protected static ?string $pluralModelLabel = 'خيارات التقطيع والتغليف';
    protected static ?string $modelLabel = 'خيار';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('النوع')
                    ->options([
                        'cutting' => 'تقطيع',
                        'packaging' => 'تغليف',
                        'liver' => 'كبدة',
                        'head' => 'رأس',
                    ])
                    ->required(),

                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),

                TextInput::make('extra_price')
                    ->label('السعر الإضافي')
                    ->numeric()
                    ->default(0)
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
                TextColumn::make('type')
                    ->label('النوع')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),

                TextColumn::make('extra_price')
                    ->label('السعر الإضافي')
                    ->sortable(),

                BooleanColumn::make('is_active')
                    ->label('نشط'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
                ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),

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
            'index' => Pages\ListPackagingOptions::route('/'),
            'create' => Pages\CreatePackagingOption::route('/create'),
            'edit' => Pages\EditPackagingOption::route('/{record}/edit'),
        ];
    }
}
