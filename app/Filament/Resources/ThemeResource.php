<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeResource\Pages;
use App\Models\Theme;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-chat-bubble-left-right'; }
    public static function getNavigationLabel(): string { return 'Темы форума'; }
    public static function getModelLabel(): string { return 'Тема'; }
    public static function getPluralModelLabel(): string { return 'Темы'; }
    public static function getNavigationGroup(): string { return 'Модерация'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Заголовок')->required()->maxLength(255),
            Textarea::make('content')->label('Содержание')->rows(6)->required(),
            Toggle::make('is_approved')->label('Одобрено')->default(true),
            Toggle::make('is_pinned')->label('Закреплено'),
            Toggle::make('is_closed')->label('Закрыто'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Заголовок')->searchable()->limit(50),
                TextColumn::make('user.name')->label('Автор')->searchable(),
                TextColumn::make('category.name')->label('Категория'),
                IconColumn::make('is_approved')->label('Одобрено')->boolean(),
                IconColumn::make('is_pinned')->label('Закреп.')->boolean(),
                IconColumn::make('is_closed')->label('Закрыто')->boolean(),
                TextColumn::make('created_at')->label('Создана')->dateTime('d.m.Y')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Одобрение')
                    ->trueLabel('Одобренные')
                    ->falseLabel('На модерации'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Одобрить')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Theme $record) => !$record->is_approved)
                    ->action(fn (Theme $record) => $record->update(['is_approved' => true])),

                Action::make('toggle_close')
                    ->label(fn (Theme $record) => $record->is_closed ? 'Открыть' : 'Закрыть')
                    ->icon('heroicon-o-lock-closed')
                    ->action(fn (Theme $record) => $record->update(['is_closed' => !$record->is_closed])),

                EditAction::make(),
                DeleteAction::make(),
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
