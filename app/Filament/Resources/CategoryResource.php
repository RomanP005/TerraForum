<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-folder';
    }

    public static function getNavigationLabel(): string { return 'Категории'; }
    public static function getModelLabel(): string { return 'Категория'; }
    public static function getPluralModelLabel(): string { return 'Категории'; }
    public static function getNavigationGroup(): string { return 'Управление'; }

    public static function canAccess(): bool
    {
        return auth('admin')->user()?->hasRole('admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Название')->required()->maxLength(150),
            Select::make('parent_id')->label('Родительская категория')->relationship('parent', 'name')->nullable()->placeholder('Корневая категория'),
            Textarea::make('description')->label('Описание')->rows(2),
            TextInput::make('sort_order')->label('Порядок')->numeric()->default(0),
            Toggle::make('is_active')->label('Активна')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Название')->searchable(),
                TextColumn::make('parent.name')->label('Родитель')->default('—'),
                TextColumn::make('sort_order')->label('Порядок')->sortable(),
                IconColumn::make('is_active')->label('Активна')->boolean(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
