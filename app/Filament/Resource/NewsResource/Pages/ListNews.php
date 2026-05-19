<?php
// app/Filament/Resources/NewsResource/Pages/ListNews.php
namespace App\Filament\Resource\NewsResource\Pages;

use App\Filament\Resource\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Новая статья')];
    }
}
