<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopItemResource\Pages;
use App\Models\ShopItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShopItemResource extends Resource
{
    protected static ?string $model = ShopItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Quản lý cửa hàng';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_shop_id')
                    ->label('Cửa hàng')
                    ->relationship('userShop', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Tên mặt hàng')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->label('Giá')
                    ->numeric()
                    ->prefix('đ')
                    ->minValue(0),
                Forms\Components\FileUpload::make('featured_image')
                    ->label('Ảnh mặt hàng')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('shop_items')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->maxSize(5120)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Kích hoạt')
                    ->default(true),
                Forms\Components\TextInput::make('stock_quantity')
                    ->label('Tồn kho')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(100),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Ảnh')
                    ->getStateUsing(fn($record) => $record?->featured_image ? asset('storage/' . $record->featured_image) : null)
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userShop.name')
                    ->label('Cửa hàng')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->money('vnd', true)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Tồn kho')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y H:i')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopItems::route('/'),
            'create' => Pages\CreateShopItem::route('/create'),
            'edit' => Pages\EditShopItem::route('/{record}/edit'),
        ];
    }
}



