<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-users'; }
    public static function getNavigationLabel(): string { return 'Пользователи'; }
    public static function getModelLabel(): string { return 'Пользователь'; }
    public static function getPluralModelLabel(): string { return 'Пользователи'; }
    public static function getNavigationGroup(): string { return 'Управление'; }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Никнейм')->required()->maxLength(100),
            TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
            TextInput::make('region')->label('Регион')->maxLength(100),
            Select::make('roles')->label('Роль')->relationship('roles', 'name')->multiple()->preload(),
            TextInput::make('rating')->label('Репутация')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Никнейм')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('roles.name')->label('Роль')->badge(),
                TextColumn::make('rating')->label('Репутация')->sortable(),
                TextColumn::make('created_at')->label('Регистрация')->dateTime('d.m.Y')->sortable(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit'  => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
