<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VerificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'verifications';
    protected static ?string $title = 'التحققات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('النوع')
                    ->options([
                        'email' => 'بريد إلكتروني',
                        'phone' => 'هاتف',
                        'reset_password' => 'إعادة تعيين كلمة المرور',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('otp')
                    ->label('رمز التحقق')
                    ->required()
                    ->maxLength(6),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->required(),
                Forms\Components\Toggle::make('is_used')
                    ->label('تم استخدامه')
                    ->default(false),
                Forms\Components\DateTimePicker::make('used_at')
                    ->label('تاريخ الاستخدام')
                    ->nullable(),
                Forms\Components\TextInput::make('attempts')
                    ->label('المحاولات')
                    ->numeric()
                    ->default(0)
                    ->maxValue(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع'),
                Tables\Columns\TextColumn::make('otp')
                    ->label('رمز التحقق'),
                Tables\Columns\IconColumn::make('is_used')
                    ->label('تم استخدامه')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->dateTime(),
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
