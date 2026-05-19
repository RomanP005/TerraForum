<?php

namespace App\Filament\Resource;

use App\Filament\Resource\NewsResource\Pages;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Новости';
    protected static ?string $modelLabel = 'Новость';
    protected static ?string $pluralModelLabel = 'Новости';
    protected static ?string $navigationGroup = 'Контент';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Основное')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Заголовок')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true),

                    Forms\Components\Select::make('news_category')
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

                    Forms\Components\Textarea::make('excerpt')
                        ->label('Аннотация')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Краткое описание для карточки и мета-тегов'),

                    Forms\Components\RichEditor::make('content')
                        ->label('Содержание')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'heading', 'bulletList', 'orderedList',
                            'blockquote', 'link',
                        ]),
                ])->columns(2),

            Forms\Components\Section::make('Публикация')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Опубликовать')
                        ->default(false)
                        ->live(),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Дата публикации')
                        ->default(now())
                        ->visible(fn (Forms\Get $get) => $get('is_published')),
                ])->columns(2),

            Forms\Components\Section::make('Обложка')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                        ->label('Обложка')
                        ->collection('cover')
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120)
                        ->helperText('Рекомендуемый размер: 1200×800 px'),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->label('')
                    ->collection('cover')
                    ->conversion('card')
                    ->width(60)
                    ->height(40)
                    ->rounded(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('news_category')
                    ->label('Категория')
                    ->colors([
                        'success' => 'Агрономия',
                        'warning' => 'Рынок',
                        'info'    => 'Погода',
                    ]),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Опубл.')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Дата публ.')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Просмотры')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Публикация')
                    ->trueLabel('Опубликованные')
                    ->falseLabel('Черновики'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit'   => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
