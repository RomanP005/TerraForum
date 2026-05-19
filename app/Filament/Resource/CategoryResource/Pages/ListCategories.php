<?php

namespace App\Filament\Resource\CategoryResource\Pages;

use App\Filament\Resource\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Добавить категорию'),
        ];
    }
}
