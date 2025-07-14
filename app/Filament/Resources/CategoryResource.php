<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Quản lý nội dung';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin danh mục')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Heroicon (ví dụ: heroicon-o-bookmark)')
                            ->maxLength(100),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Màu sắc'),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Ảnh danh mục')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('parent_id')
                            ->label('Danh mục cha')
                            ->options(fn () => \App\Models\Category::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->placeholder('Không có')
                            ->disableOptionWhen(fn ($value, $get) => $value == $get('id')),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Thứ tự sắp xếp')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

   
}
