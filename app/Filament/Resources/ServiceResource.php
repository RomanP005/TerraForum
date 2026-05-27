<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-wrench-screwdriver';
    }

    public static function getNavigationLabel(): string { return 'Услуги'; }
    public static function getModelLabel(): string { return 'Услуга'; }
    public static function getPluralModelLabel(): string { return 'Услуги'; }
    public static function getNavigationGroup(): string { return 'Модерация'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('title')
                ->label('Название')
                ->required()
                ->maxLength(200),

            Select::make('service_category')
                ->label('Категория')
                ->options(array_combine(Service::categories(), Service::categories()))
                ->searchable(),

            Textarea::make('description')
                ->label('Описание')
                ->rows(5)
                ->required(),

            TextInput::make('region')->label('Регион')->maxLength(100),
            TextInput::make('city')->label('Город')->maxLength(100),
            TextInput::make('phone')->label('Телефон')->maxLength(20),

            TextInput::make('price')->label('Цена')->numeric(),
            TextInput::make('price_unit')->label('Единица цены')->default('руб.'),

            Toggle::make('price_negotiable')->label('Договорная'),
            Toggle::make('is_approved')->label('Одобрено')->default(false),
            Toggle::make('is_active')->label('Активно')->default(true),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable(),

                TextColumn::make('service_category')
                    ->label('Категория')
                    ->limit(25),

                TextColumn::make('region')
                    ->label('Регион'),

                IconColumn::make('is_approved')
                    ->label('Одобрено')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Активно')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Статус')
                    ->trueLabel('Одобренные')
                    ->falseLabel('На модерации'),
            ])
            ->actions([
                // Одобрить одним кликом
                Action::make('approve')
                    ->label('Одобрить')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Service $record) => !$record->is_approved)
                    ->action(fn (Service $record) => $record->update(['is_approved' => true])),

                // Снять с публикации
                Action::make('reject')
                    ->label('Отклонить')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Service $record) => $record->is_approved)
                    ->action(fn (Service $record) => $record->update(['is_approved' => false])),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'edit'  => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
