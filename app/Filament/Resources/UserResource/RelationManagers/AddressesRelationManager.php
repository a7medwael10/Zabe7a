<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';
    protected static ?string $title = 'العناوين';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('الوصف')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('country')
                    ->label('الدولة')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('city')
                    ->label('المدينة')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('district')
                    ->label('الحي')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('street')
                    ->label('الشارع')
                    ->required()
                    ->maxLength(150),
                Forms\Components\TextInput::make('postal_code')
                    ->label('الرمز البريدي')
                    ->required()
                    ->maxLength(20),
                Forms\Components\Textarea::make('building_description')
                    ->label('وصف المبنى')
                    ->nullable(),
                Forms\Components\TextInput::make('latitude')
                    ->label('خط العرض')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('longitude')
                    ->label('خط الطول')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Toggle::make('is_primary')
                    ->label('العنوان الأساسي')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('الوصف'),
                Tables\Columns\TextColumn::make('city')
                    ->label('المدينة'),
                Tables\Columns\TextColumn::make('street')
                    ->label('الشارع'),
                Tables\Columns\IconColumn::make('is_primary')
                    ->label('أساسي')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }
}
