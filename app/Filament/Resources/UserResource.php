<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\VerificationsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $pluralModelLabel = 'المستخدمون';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\FileUpload::make('avatar')
                  ->label('الصورة الرمزية')
                  ->image()
                  ->directory('users/avatar')
                  ->nullable()
                  ->columnSpanFull(),
                  Forms\Components\TextInput::make('first_name')
                      ->label('الاسم الأول')
                      ->required()
                      ->validationMessages([
                          'required' => 'الاسم الأول مطلوب.',
                      ]),

                  Forms\Components\TextInput::make('last_name')
                      ->label('الاسم الأخير')
                      ->required()
                      ->validationMessages([
                          'required' => 'الاسم الأخير مطلوب.',
                      ]),

                  Forms\Components\TextInput::make('phone')
                      ->label('رقم الهاتف')
                      ->required()
                      ->unique(ignoreRecord: true)
                      ->validationMessages([
                          'required' => 'رقم الهاتف مطلوب.',
                          'unique' => 'رقم الهاتف مستخدم بالفعل.',
                      ]),

                  Forms\Components\TextInput::make('email')
                      ->label('البريد الإلكتروني')
                      ->email()
                      ->required()
                      ->unique(ignoreRecord: true)
                      ->validationMessages([
                          'required' => 'البريد الإلكتروني مطلوب.',
                          'email' => 'يجب إدخال بريد إلكتروني صحيح.',
                          'unique' => 'البريد الإلكتروني مستخدم بالفعل.',
                      ]),

                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(fn ($record) => !$record)
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => !empty($state))
                    ->validationMessages([
                        'required' => 'كلمة المرور مطلوبة عند إنشاء المستخدم.',
                    ]),

                Forms\Components\Select::make('gender')
                  ->label('الجنس')
                  ->options(['male' => 'ذكر', 'female' => 'أنثى'])
                  ->nullable()
                  ->validationMessages([
                      'in' => 'يرجى اختيار قيمة صحيحة للجنس.',
                  ]),

                Forms\Components\Hidden::make('agree_terms')
                    ->default(true),

                Forms\Components\Hidden::make('is_email_verified')
                    ->default(true),

                Forms\Components\Hidden::make('email_verified_at')
                    ->default(now()),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('الاسم الأول')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('الاسم الأخير')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('الجنس'),
                Tables\Columns\IconColumn::make('is_email_verified')
                    ->label('تم التحقق من البريد')
                    ->boolean(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('الجنس')
                    ->options(['male' => 'ذكر', 'female' => 'أنثى']),
            ])
            ->actions([
              Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            VerificationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}/view'),
        ];
    }


}
