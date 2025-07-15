<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RatingResource\Pages;
use App\Filament\Resources\RatingResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\Rating;
use Filament\Forms\Components\TextInput;


class RatingResource extends Resource
{
  protected static ?string $model = Rating::class;

  protected static ?string $navigationIcon = 'heroicon-o-star';

  protected static ?string $navigationGroup = 'Quản lý nội dung';

  protected static ?int $navigationSort = 6;
  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('rating')
          ->required()
          ->numeric()
          ->minValue(1)
          ->maxValue(5),
        TextInput::make('comment')
          ->required()
          ->maxLength(255),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('user.name')
          ->label('Người đánh giá')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('recipe.title')
          ->label('Công thức')
          ->searchable()
          ->sortable()
          ->limit(50),
        Tables\Columns\TextColumn::make('rating')
          ->label('Điểm')
          ->formatStateUsing(fn(int $state): string => str_repeat('⭐', $state))
          ->sortable()
          ->badge()
          ->color(fn(int $state): string => match (true) {
            $state >= 4 => 'success',
            $state >= 3 => 'warning',
            $state >= 2 => 'info',
            default => 'danger',
          }),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Tạo lúc')
          ->dateTime('d/m/Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('rating')
          ->label('Điểm đánh giá')
          ->options([
            5 => '5 sao',
            4 => '4 sao',
            3 => '3 sao',
            2 => '2 sao',
            1 => '1 sao',
          ]),
        Tables\Filters\SelectFilter::make('user')
          ->label('Người đánh giá')
          ->relationship('user', 'name')
          ->searchable()
          ->preload(),
        Tables\Filters\SelectFilter::make('recipe')
          ->label('Công thức')
          ->relationship('recipe', 'title')
          ->searchable()
          ->preload(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make(),
          Tables\Actions\EditAction::make(),
        ]),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('created_at', 'desc');
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListRatings::route('/'),
      'create' => Pages\CreateRating::route('/create'),
      'edit' => Pages\EditRating::route('/{record}/edit'),
    ];
  }
}
