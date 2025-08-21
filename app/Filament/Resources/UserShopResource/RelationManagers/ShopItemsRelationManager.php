<?php

namespace App\Filament\Resources\UserShopResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ShopItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'shopItems';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}


