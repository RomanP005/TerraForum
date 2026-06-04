<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-newspaper';
    }

    public static function getNavigationLabel(): string
    {
        return 'Новости';
    }

    public static function getModelLabel(): string
    {
        return 'Новость';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Новости';
    }

    public static function getNavigationGroup(): string
    {
        return 'Контент';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('title')
                ->label('Заголовок')
                ->required()
                ->maxLength(255),

            Select::make('news_category')
                ->label('Категория')
                ->options([
                    'Агрономия'  => 'Агрономия',
                    'Рынок'      => 'Рынок',
                    'Погода'     => 'Погода',
                    'Технологии' => 'Технологии',
                    'События'    => 'События',
                    'Советы'     => 'Советы',
                ])
                ->searchable(),

            Textarea::make('excerpt')
                ->label('Аннотация')
                ->rows(3)
                ->maxLength(500),

            RichEditor::make('content')
                ->label('Содержание')
                ->required()
                ->toolbarButtons(['bold', 'italic', 'underline', 'h2', 'h3', 'bulletList', 'orderedList', 'blockquote', 'link']),


            \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                ->label('Обложка статьи')
                ->collection('cover')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->helperText('JPEG / PNG / WebP до 5 МБ'),

            \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                ->label('Дополнительные фотографии')
                ->collection('gallery')
                ->multiple()
                ->image()
                ->maxSize(5120)
                ->maxFiles(10)
                ->reorderable()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->helperText('До 10 фото, JPEG / PNG / WebP'),

            Toggle::make('is_published')
                ->label('Опубликовать')
                ->default(false),

            DateTimePicker::make('published_at')
                ->label('Дата публикации')
                ->default(now()),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Заголовок')->searchable()->limit(50),
                TextColumn::make('news_category')->label('Категория'),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('published_at')->label('Дата публ.')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('views_count')->label('Просмотры')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Публикация')
                    ->trueLabel('Опубликованные')
                    ->falseLabel('Черновики'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
