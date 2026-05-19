<?php

namespace App\Filament\Resource;

use App\Filament\Resource\ThemeResource\Pages;
use App\Models\Theme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Темы форума';
    protected static ?string $modelLabel = 'Тема';
    protected static ?string $pluralModelLabel = 'Темы';
    protected static ?string $navigationGroup = 'Модерация';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Заголовок')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('content')
                ->label('Содержание')
                ->rows(6)
                ->required(),

            Forms\Components\Toggle::make('is_approved')
                ->label('Одобрено')
                ->default(true),

            Forms\Components\Toggle::make('is_pinned')
                ->label('Закреплено'),

            Forms\Components\Toggle::make('is_closed')
                ->label('Закрыто'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория'),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Одобрено')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_pinned')
                    ->label('Закреп.')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_closed')
                    ->label('Закрыто')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Одобрение')
                    ->trueLabel('Одобренные')
                    ->falseLabel('На модерации'),

                Tables\Filters\TernaryFilter::make('is_pinned')
                    ->label('Закреплённые'),
            ])
            ->actions([
                // Быстрое одобрение
                Tables\Actions\Action::make('approve')
                    ->label('Одобрить')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Theme $record) => !$record->is_approved)
                    ->action(fn (Theme $record) => $record->update(['is_approved' => true])),

                // Закрыть/открыть
                Tables\Actions\Action::make('toggle_close')
                    ->label(fn (Theme $record) => $record->is_closed ? 'Открыть' : 'Закрыть')
                    ->icon('heroicon-o-lock-closed')
                    ->action(fn (Theme $record) => $record->update(['is_closed' => !$record->is_closed])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListThemes::route('/'),
            'edit'  => Pages\EditTheme::route('/{record}/edit'),
        ];
    }
}
