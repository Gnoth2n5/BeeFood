<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserShopResource\Pages;
use App\Filament\Resources\UserShopResource\RelationManagers\ShopItemsRelationManager;
use App\Models\UserShop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UserShopResource extends Resource
{
    protected static ?string $model = UserShop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Quản lý cửa hàng';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cửa hàng')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Người sở hữu')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Tên cửa hàng')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $state, callable $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->maxLength(30),
                        Forms\Components\TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('Địa chỉ')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('latitude')
                            ->label('Vĩ độ (latitude)')
                            ->numeric()
                            ->minValue(-90)
                            ->maxValue(90)
                            ->step(0.000001)
                            ->nullable(),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Kinh độ (longitude)')
                            ->numeric()
                            ->minValue(-180)
                            ->maxValue(180)
                            ->step(0.000001)
                            ->nullable(),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('shops')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Ảnh')
                    ->getStateUsing(fn($record) => $record?->featured_image ? asset('storage/' . $record->featured_image) : null)
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên cửa hàng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Chủ sở hữu')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái')
                    ->placeholder('Tất cả')
                    ->trueLabel('Đang hoạt động')
                    ->falseLabel('Không hoạt động'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ShopItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserShops::route('/'),
            'create' => Pages\CreateUserShop::route('/create'),
            'edit' => Pages\EditUserShop::route('/{record}/edit'),
        ];
    }
}




class UserShopResourceVip extends Resource
{
    protected static ?string $model = UserShop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Quản lý cửa hàng';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->required(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn(string $state, callable $set) => $set('slug', Str::slug($state))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('address')->maxLength(255),
            Forms\Components\TextInput::make('phone')->maxLength(30),
            Forms\Components\TextInput::make('website')->maxLength(255),
            Forms\Components\Textarea::make('description')->columnSpanFull(),
            Forms\Components\FileUpload::make('featured_image')->disk('public')->image(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('user.name')->label('Người dùng'),
            Tables\Columns\TextColumn::make('address')->limit(30),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserShops::route('/'),
            'create' => Pages\CreateUserShop::route('/create'),
            'edit' => Pages\EditUserShop::route('/{record}/edit'),
        ];
    }
}


